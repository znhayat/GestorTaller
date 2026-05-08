# 9.1. Gestió d'usuaris i rols

El sistema de gestió del taller està dissenyat per separar clarament les responsabilitats i protegir la informació sensible mitjançant un sistema robust de rols i permisos.

## 👥 Rols del Sistema

El programari diferencia dos perfils d'usuari amb capacitats clarament definides:

### 🛠️ Rol Administrador (Cap)
És l'usuari amb control total sobre l'aplicació. Les seves funcions principals inclouen:
- **Gestió Econòmica:** Accés complet a l'historial de facturació, possibilitat d'esborrar factures i marcar cobraments.
- **Anàlisi de Negoci:** Visualització del resum de vendes i KPIs econòmics al panell principal.
- **Control d'Inventari:** Gestió de preus de materials, edició i eliminació d'articles del catàleg.
- **Administració de Personal:** Control total sobre els comptes d'usuari, canvis de contrasenya i, especialment, l'aprovació de nous registres.

### 🔧 Rol Operari (Empleat)
Perfil enfocat al treball tècnic i diari al taller. Les seves capacitats estan limitades per evitar errors en dades crítiques:
- **Operacions Diàries:** Pot moure targetes als taulells Kanban (Recepció i Producció) per actualitzar l'estat dels vehicles.
- **Documentació Visual:** Capacitat per pujar fotos dels treballs realitzats (encara que no pot esborrar-les).
- **Consulta de Dades:** Pot consultar la llista de clients i els detalls dels vehicles.
- **Restriccions de Seguretat:** No té accés a la facturació, no pot veure resums de vendes, no pot modificar preus de materials ni gestionar altres usuaris.

---

## 🔒 Control d'Aprovació i Seguretat

Per garantir que cap persona aliena al taller pugui accedir a les dades dels clients, s'ha implementat un flux d'alta controlat:

1.  **Registre Bloquejat:** Quan un nou usuari es registra al sistema, el seu compte queda en estat **"Bloquejat"** per defecte.
2.  **Avis d'Administració:** L'Administrador veurà un avís visual a la secció de "Usuarios" indicant quants comptes estan pendents de revisió.
3.  **Activació Manual:** L'Administrador ha d'entrar manualment a la fitxa de l'usuari i canviar el seu estat a **"Aprobado"** per permetre-li l'accés.
4.  **Autonomia de Baixa:** Cada usuari té la facultat de donar-se de baixa del sistema si ja no treballarà al taller, facilitant el manteniment de la base de dades.

---

## 🛡️ Protecció de Comptes Crítics
El sistema inclou una salvaguarda especial per evitar que el taller es quedi sense administrador:
- No es pot eliminar l'últim compte d'administrador del sistema.
- Les opcions de borrat de comptes administratius estan protegides per evitar errors fatals en la gestió del negoci.
