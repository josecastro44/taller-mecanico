# Refactorización Exhaustiva — Taller Mecánico AutoSys

Revisión de código completa para garantizar sincronización 100% entre módulos financieros y perfeccionar reportes PDF.

---

## Problemas Identificados (Hallazgos de Auditoría)

Tras revisar los 15 controladores, 17 modelos, 10 plantillas PDF, 2 servicios y 28 migraciones, se detectaron los siguientes problemas:

### 🔴 Errores Críticos (Lógica Financiera Rota)

| # | Problema | Archivo | Impacto |
|---|---------|---------|---------|
| 1 | **Repuesto usa campo inexistente `precio`** — `VentaController` L56 usa `$repuesto->precio ?? $repuesto->precio_venta`, pero la tabla solo tiene `precio_venta` y `costo_adquisicion`. El fallback a `precio` retornará siempre null | [VentaController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/VentaController.php#L56) | Precio de venta podría calcularse en $0 |
| 2 | **Mismo bug en MecanicoController** — L78: `$repuesto->precio ?? $repuesto->precio_venta ?? 0` | [MecanicoController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/MecanicoController.php#L78) | Repuestos agregados a O.S. con precio $0 |
| 3 | **PDF Reporte General referencia `$factura->monto_iva` — columna eliminada** por migración `remove_iva_igtf_from_facturas_table`. Genera error 500 al intentar imprimir | [reporte_general.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/reporte_general.blade.php#L117) | PDF no se genera |
| 4 | **PDF Inventario referencia `$rep->precio_compra`** — campo inexistente, el real es `costo_adquisicion` | [inventario_general.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/inventario_general.blade.php#L37) | Muestra $0 para costos |
| 5 | **`FinanzasController::index()` calcula utilidad sin incluir ventas mostrador** — Solo suma `Factura::sum('total_facturado')` para ingresos, ignorando completamente las ventas de repuestos (`Venta`) | [FinanzasController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/FinanzasController.php#L24) | Utilidad neta es incorrecta |
| 6 | **Egresos por sueldos se calculan como total plano** — `Empleado::sum('sueldo_base')` no filtra por mes, cuenta todos los empleados históricos independiente del período | [FinanzasController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/FinanzasController.php#L29) | Distorsiona el balance mensual |
| 7 | **Factura doble-query innecesaria** — `registrarPago()` hace `Factura::findOrFail()` dos veces (L140 y L146) | [FinanzasController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/FinanzasController.php#L140-L146) | Desperdicio de recursos |
| 8 | **Ticket de venta con número aleatorio** — `'VT-' . rand(1000, 9999)` puede generar duplicados | [VentaController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/VentaController.php#L61) | Colisión de tickets |
| 9 | **Orden de Compra con número aleatorio** — `'OC-' . date('Y') . '-' . rand(100, 999)` puede duplicarse | [CompraController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/CompraController.php#L46) | Colisión de números de orden |

### 🟡 Problemas de Sincronización y Consistencia

| # | Problema | Archivo | Impacto |
|---|---------|---------|---------|
| 10 | **Ventas al mostrador no registran impacto en Finanzas** — El dashboard de Finanzas solo muestra facturas de O.S., no ventas de mostrador | [FinanzasController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/FinanzasController.php#L19-L44) | Visión parcial de caja |
| 11 | **`ReporteController` egresos usa sueldos fijos mensuales** — L86: pone egresos solo si hay ingresos ese mes, y usa la suma total de sueldos sin filtrar | [ReporteController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/ReporteController.php#L80-L87) | Gráfico de balance financiero distorsionado |
| 12 | **Falta `decimal:2` cast en Venta y DetalleVenta** — Los montos no tienen cast, pueden aparecer con decimales inconsistentes | [Venta.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Venta.php) / [DetalleVenta.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/DetalleVenta.php) | Inconsistencia decimal |
| 13 | **Falta `decimal:2` cast en Compra y DetalleCompra** — Misma situación | [Compra.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Compra.php) / [DetalleCompra.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/DetalleCompra.php) | Inconsistencia decimal |
| 14 | **Empleado no tiene cast para `sueldo_base` ni `comision`** | [Empleado.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Empleado.php) | Montos sin redondeo |
| 15 | **Repuesto no tiene cast para campos monetarios** | [Repuesto.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Repuesto.php) | Precios con decimales inconsistentes |
| 16 | **`RepuestoController::store()` usa `$request->all()`** — peligro de mass assignment, puede incluir campos inesperados | [RepuestoController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/RepuestoController.php#L55-L76) | Seguridad |
| 17 | **BcvService retorna `precio: 0` en fallback** — Si la API falla, toda conversión Bs queda en $0, sin forma de detectar que fue un error desde la UI de finanzas | [BcvService.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Services/BcvService.php#L75-L84) | Conversiones a Bs incorrectas |
| 18 | **`RecepcionController` y descuento de inventario en O.S.** — El mecánico descuenta stock al agregar repuesto, pero si la O.S. es cancelada no hay mecanismo de devolución al inventario | [MecanicoController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/MecanicoController.php#L75-L76) | Inventario puede quedar descuadrado |

---

## Propuesta de Cambios

### Componente 1: Corrección de Lógica Financiera

#### [MODIFY] [VentaController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/VentaController.php)
- **L56**: Corregir referencia a `precio_venta` directamente (eliminar fallback a campo `precio` inexistente)
- **L61**: Reemplazar `rand()` con secuencia segura basada en `Venta::latest('id')`
- Añadir `round()` consistente con 2 decimales en todo cálculo

#### [MODIFY] [MecanicoController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/MecanicoController.php)
- **L78**: Corregir referencia: usar `$repuesto->precio_venta` directamente
- Añadir `round()` al precio unitario

#### [MODIFY] [FinanzasController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/FinanzasController.php)
- **L24**: Sumar ingresos de Facturas + Ventas Mostrador para ingresos brutos reales
- **L29**: Filtrar sueldos empleados con lógica mensual (sueldo * cantidad de empleados activos)
- **L30**: Incluir también ingresos de ventas mostrador en el balance
- **L140-L146**: Eliminar la doble consulta `findOrFail` redundante
- Pasar `ventasMostrador` a la vista para visibilidad completa

#### [MODIFY] [CompraController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/CompraController.php)
- **L46**: Reemplazar `rand()` con secuencia segura basada en último ID
- Añadir `round()` con 2 decimales al subtotal

#### [MODIFY] [ReporteController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/ReporteController.php)
- **L80-L87**: Usar gastos operativos pagados + sueldos para los egresos reales por mes (consultar `AsientoContable` en lugar de calcular manualmente)
- Incluir ventas mostrador en `ingresosData`

---

### Componente 2: Endurecimiento de Modelos (Casts y Precisión)

#### [MODIFY] [Venta.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Venta.php)
- Añadir `$casts = ['total' => 'decimal:2']`
- Corregir indentación del método `detalles()`

#### [MODIFY] [DetalleVenta.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/DetalleVenta.php)
- Añadir `$casts = ['precio_unitario' => 'decimal:2', 'subtotal' => 'decimal:2']`

#### [MODIFY] [Compra.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Compra.php)
- Añadir `$casts = ['total' => 'decimal:2']`

#### [MODIFY] [DetalleCompra.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/DetalleCompra.php)
- Añadir `$casts = ['precio_unitario' => 'decimal:2', 'subtotal' => 'decimal:2']`

#### [MODIFY] [Empleado.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Empleado.php)
- Añadir `$casts = ['sueldo_base' => 'decimal:2', 'comision' => 'decimal:2']`

#### [MODIFY] [Repuesto.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Models/Repuesto.php)
- Añadir `$casts = ['costo_adquisicion' => 'decimal:2', 'precio_venta' => 'decimal:2']`

#### [MODIFY] [RepuestoController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/RepuestoController.php)
- **L55**: Reemplazar `$request->all()` con `$request->only([...])` (campos explícitos)
- **L76**: Igual para el update

---

### Componente 3: Rediseño de PDFs Administrativos Profesionales

Se rediseñarán las **10 plantillas PDF** con un sistema de diseño unificado que incluya:
- Encabezado corporativo consistente (logo/nombre de empresa, RIF, dirección, teléfono)
- Numeración de página
- Firmas y sellos en los que corresponda
- Colores corporativos consistentes (#263A47 principal, #16a34a ingresos, #dc2626 egresos)
- Tablas con bordes definidos y tipografía legible
- Sección de condiciones/observaciones donde aplique
- Pie de página con fecha de generación y marca del sistema

#### [MODIFY] [factura.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/factura.blade.php)
- Rediseño completo: incluir datos del cliente de la O.S. (placa, vehículo), desglose de servicios y repuestos individuales (no solo subtotales agrupados), sección de estado de pago, equivalente en Bs con tasa BCV, condiciones de pago
- Cargar datos de la O.S. vinculada para desglose

#### [MODIFY] [libro.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/libro.blade.php)
- Añadir columna de estado de pago, totales por estado, pie de página con número de página

#### [MODIFY] [reporte_general.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/reporte_general.blade.php)
- **Eliminar referencia a `monto_iva`** (columna eliminada)
- Reemplazar columna IVA por columna de estado de pago
- Mejorar diseño de KPIs con bordes definidos para DomPDF (no usar `flex`)

#### [MODIFY] [libro_diario.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/libro_diario.blade.php)
- Mejorar totales con `<table>` en lugar de `display: flex` (incompatible con DomPDF)
- Añadir número de página y firma

#### [MODIFY] [balance_general.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/balance_general.blade.php)
- Corregir layout de cajas de resumen (usar `<table>` en vez de `display: inline-block`)
- Añadir sección de nómina y gastos operativos separada

#### [MODIFY] [gastos_operativos.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/gastos_operativos.blade.php)
- Añadir resumen por categoría, número de página

#### [MODIFY] [ticket_venta.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/ticket_venta.blade.php)
- Añadir precio unitario por línea

#### [MODIFY] [reporte_ventas.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/reporte_ventas.blade.php)
- Añadir resumen por método de pago, pie de página

#### [MODIFY] [inventario_general.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/inventario_general.blade.php)
- **Corregir `precio_compra` → `costo_adquisicion`**
- Usar `stock_minimo` para marcar críticos correctamente
- Añadir columna de margen de ganancia, resumen estadístico

#### [MODIFY] [repuesto_individual.blade.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/resources/views/pdfs/repuesto_individual.blade.php)
- Mejorar diseño con datos adicionales (vehículo compatible, margen)

---

### Componente 4: Ajuste en Controlador de Factura PDF

#### [MODIFY] [FinanzasController.php](file:///d:/proyectos%20acutlaes/repos/Nueva%20carpeta/taller-mecanico/app/Http/Controllers/FinanzasController.php) — `imprimirFactura()`
- Cargar la relación `ordenServicio.servicios` y `ordenServicio.repuestos` junto con la factura
- Pasar datos de la O.S. a la vista PDF para el desglose detallado
- Pasar tasa BCV actual para mostrar equivalente en bolívares

---

## User Review Required

> [!IMPORTANT]
> **Datos del encabezado corporativo para los PDFs.** Actualmente los PDFs usan nombres variados ("CTR - Castro Technology Research", "CTR AUTO PARTS", "AutoSys"). Necesito saber:
> - ¿Cuál es el nombre oficial de la empresa para los documentos?
> - ¿Tienen un RIF real para usar? (actualmente dice `J-00000000-0`)
> - ¿Dirección y teléfono para incluir en las facturas?

> [!WARNING]
> **Sobre el IVA/IGTF**: Veo que se eliminaron las columnas de IVA e IGTF de facturas, pero el PDF de `reporte_general.blade.php` aún referencia `$factura->monto_iva`. Esto **causa un error 500** al intentar generar ese PDF. ¿La decisión es permanente de no cobrar impuestos, o planean reactivar IVA más adelante?

> [!IMPORTANT]
> **Descuentos**: Mencionaste que un descuento debe impactar toda la plataforma. Actualmente no hay mecanismo de descuentos implementado en el sistema. ¿Quieres que lo preparemos como parte de esta refactorización (campo `descuento` en facturas y ventas), o lo dejamos para una fase posterior? En esta refactorización garantizaré que la estructura esté lista para soportarlo.

---

## Verificación

### Pruebas Funcionales
1. Generar cada uno de los 10 PDFs y verificar que no hay errores 500
2. Registrar una venta mostrador y verificar:
   - Stock se descuenta correctamente
   - Asiento contable se crea con monto correcto
   - Aparece en el dashboard de Finanzas
3. Registrar una O.S. completa (recepción → reparación → finalizado → factura → pago) y verificar:
   - Todos los montos cuadran con 2 decimales
   - El asiento contable se crea
   - El estado de la O.S. cambia a "Entregado"
   - La factura aparece en el libro de ventas
4. Registrar un gasto operativo y pagarlo → verificar que aparece en contabilidad
5. Verificar que los totales del Balance General coinciden con la suma de los asientos del Libro Diario

### Verificación de PDFs
- Generar y revisar visualmente los 10 PDFs para confirmar formato, datos y ausencia de errores
