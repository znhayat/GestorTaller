# 4.2. ESPECIFICACIÓ DETALLADA DE REQUERIMENTS FUNCIONALS (COMPLET)

Aquest document detalla totes les funcionalitats del sistema GestorTaller, garantint que no hi hagi buits lògics ni vulnerabilitats en el flux de treball.

## MÒDUL I: SEGURETAT I CONTROL D'ACCÉS
1. **Autenticació Centralitzada**: Accés mitjançant correu i contrasenya xifrada (`BCrypt`). No es permet l'accés anònim a cap part del gestor.
2. **Sistema de Rols (RBAC)**:
   - **Administrador**: Accés total, gestió d'usuaris i esborrat definitiu.
   - **Operari**: Gestió de treballs, fotos i materials, però amb restriccions en la configuració del sistema.
3. **Aprovació d'Usuaris**: Sistema de "whitelist" on un administrador ha d'aprovar manualment cada nou registre abans que pugui entrar al sistema.
4. **Protecció de Dades**: Validació estricta de formularis (Server-side) i protecció contra `CSRF`, `XSS` i `SQL Injection`.

## MÒDUL II: GESTIÓ DE CLIENTS I VEHICLES (CRM)
1. **Fitxa de Client**: Emmagatzematge de Nom, Cognoms, Telèfon (validat a 9 dígits) i Email.
2. **Cerca Dinàmica**: Cercador en temps real a la taula de clients per nom o telèfon.
3. **Historial de Vehicles**: Cada vehicle està lligat a un client. El sistema utilitza una **API de Vehicles** per seleccionar Marca i Model oficials, evitant errors d'escriptura.
4. **Trazabilitat**: Opció de consultar tots els treballs (passats i presents) d'un vehicle o client específic des de la seva fitxa.

## MÒDUL III: ASSISTENT DE NOUS TREBALLS (WIZARD)
1. **Flux de 4 Passes**:
   - **Pas 1 (Client)**: Selecció de client existent o creació ràpida.
   - **Pas 2 (Vehicle)**: Selecció o alta del vehicle mitjançant el cercador oficial.
   - **Pas 3 (Serveis)**: Selecció visual per categories (Asientos, Volantes, etc.) amb preus base preconfigurats.
   - **Pas 4 (Resum i Cita)**: Confirmació de dades i assignació de la primera cita de revisió.
2. **Aticitat de Dades**: Ús de transaccions SQL per garantir que si l'usuari tanca el navegador a mig procés, no es creïn dades incompletes.

## MÒDUL IV: GESTIÓ DE PRODUCCIÓ (KANBAN)
1. **Taulell Comercial (Recepció)**:
   - Gestió d'estats: *Pendiente*, *Presupuestado*, *Aceptado*.
   - Gestió de pressupostos: Creació de línies de cost, hores de mà d'obra i aprovació del client.
2. **Taulell de Taller (Producció)**:
   - Gestió d'estats: *En Espera*, *En Proceso*, *Finalizado*.
   - Control de temps: Registre de quan comença i quan acaba realment el treball.
3. **Lògica d'Estats**: El sistema impedeix moure una targeta a "Producció" si el pressupost no ha estat marcat com a "Acceptat".

## MÒDUL V: INVENTARI I MATERIALS
1. **Control d'Estoc Visual**: Sistema de visualització mitjançant codis de colors (Vermell/Groc) a l'inventari, que permet a l'administrador identificar d'una ullada quins materials estan esgotats o sota l'estoc mínim.
2. **Registre de Consums**: Capacitat de registrar quins materials s'han utilitzat en cada encàrrec i el seu cost, per mantenir un historial detallat dels consumos realitzats al taller.
3. **Categorització Oficial**: Organització per tipus (Tejidos, Espumas, Hilos, Adhesivos, etc.).

## MÒDUL VI: GESTIÓ ECONÒMICA
1. **Generació de Pressupostos**: Document amb desglossament de materials, mà d'obra i IVA.
2. **Facturació Automàtica**: El sistema genera una factura quan el treball es marca com a finalitzat.
3. **Control de Cobraments**: Marcador de factura *Pagada* o *Pendiente* amb registre de la data de cobrament.
4. **Exportació**: Botons d'exportació a **Excel/CSV** per a la comptabilitat mensual de factures i materials.

## MÒDUL VII: GALERIA I MÀRQUETING
1. **Registre Visual**: Pujada d'imatges vinculades a cada Ordre de Treball (OT).
2. **Mode Abans/Després**: Funcionalitat específica per mostrar l'evolució de les restauracions.
3. **Landing Page Dinàmica**: Els treballs marcats com a "Públics" apareixen automàticament a la web externa del taller per a captació de clients.

## MÒDUL VIII: MANTENIMENT I SEGURETAT TÈCNICA
1. **Soft Deletes**: Els registres esborrats es mantenen a la base de dades amb una marca de temps, permetent la seva recuperació en cas d'error.
2. **Backups**: El sistema està preparat per a còpies de seguretat periòdiques de la base de dades MySQL.
3. **Asset Management**: Sistema de **Cache-Busting** per garantir que les imatges i el logo s'actualitzin immediatament en tots els navegadors.
