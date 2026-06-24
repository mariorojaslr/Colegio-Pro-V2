# Delegaciones - Consejo Profesional de Abogados y Procuradores de La Rioja

Esta información es crucial para estructurar la separación de cobros, usuarios y administración por sedes (sucursales) en el futuro, garantizando que cada delegación mantenga orden y reportes financieros separados bajo el mismo tenant.

## CHILECITO
- **Domicilio:** 9 de Julio 52 – 1° Piso (Tribunales)
- **Teléfono:** 03825 423252
- **C.P.:** 5360
- **Secretaria:** Sra. Claudia Haas cel. 3825 556260
- **E-mail:** consejoabchilecito@gmail.com
- **Horario de atención:** 8 a 13 hs

## CHAMICAL
- **Domicilio:** Nicolás Majul Ayán (O) 74/76
- **C.P.:** 5380
- **Delegado:** Dra. Funes Quinteros María Valeria Cel. 380 4 494891
- **E-mail:** consejodeabogadosdelarioja@gmail.com
- **Horario de atención:** momentáneamente cerrado (Comunicarse con Dra Funes Quinteros)

## AIMOGASTA
- **Domicilio:** 25 de Mayo S/N (Estudio López Burgos) – Barrio Centro
- **Teléfono:** 03827 420377
- **C.P.:** 5310
- **Delegado:** Dr. Hugo Daniel Romero
- **Secretaria:** Srta. Iris Toledo Cel. 3827 402836
- **E-mail:** consejodeabogadosaimogasta@hotmail.com
- **Horario de atención:** 8:30 a 12:30 hs

## CHEPES
- **Domicilio:** Sarmiento s/n
- **C.P.:** 5470
- **Secretaria:** Sra. Gabriela Vega cel. 3821 402456
- **E-mail:** consejodeabogadosdelarioja@gmail.com
- **Horario de atención:** 8:30 a 12:30 hs

---
**Nota Arquitectónica (SaaS):**
En la base de datos, cuando se active este requerimiento, se deberá crear el modelo `Branch` o `Delegation` atado a `tenant_id`. Los `User` (abogados) y las `Transaction` (pagos de bonos, certificados) deberán tener una llave foránea `delegation_id` para poder filtrar los ingresos por ciudad sin que se mezclen.
