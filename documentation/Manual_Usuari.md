# 📘 Manual d'Usuari: Gestió de Personal i Seguretat

Aquest apartat del manual t'explicarà com gestionar l'equip del taller i com funciona el control d'accés per protegir la teva informació.

---

## 👥 9.1. Gestió d'usuaris i rols

El sistema separa clarament el que pot fer el **Cap** (Administrador) del que pot fer un **Empleat** (Operari):

### 👑 Rol Administrador (El Cap)
Té el control total del taller. Està pensat per a la persona que gestiona el negoci.
- **Accés Total:** Pot veure totes les seccions del menú sense restriccions.
- **Gestió de Diners:** Pot esborrar factures si hi ha errors, consultar el resum de vendes al panell principal i veure quant s'ha facturat recentment.
- **Preus i Materials:** És l'únic que pot modificar els preus dels materials i actualitzar l'estoc.
- **Aprovació:** És el responsable d'activar els nous usuaris que es registrin.

### 🛠️ Rol Operari (L'Empleat)
Està pensat per als mecànics o operaris que treballen dia a dia amb els vehicles.
- **Treball Diari:** Pot moure les targetes dels cotxes als taulells **Kanban** per indicar en quin estat està cada feina.
- **Fotos:** Pot pujar fotos dels treballs per mantenir un historial visual (molt útil per veure l'Abans i el Després).
- **Consulta:** Pot veure la llista de clients i buscar vehicles, però **no pot modificar preus crítics** ni esborrar dades de la comptabilitat.
- **Privacitat:** Les seccions de Factures, Usuaris i Galeria Web estan ocultes per a ells per evitar distraccions o accessos no autoritzats.

---

## 🔒 Control d'Aprovació de Nous Comptes

Per evitar que persones alienes al taller puguin tafanejar les dades dels teus clients, el sistema té un "pany" de seguretat:

1.  **Registre:** Quan algú nou es registra (per exemple, un nou empleat), el seu compte queda **"Bloquejat"** automàticament.
2.  **Avis al Cap:** Quan entris com a Administrador, veuràs una alerta a la secció de **"Usuarios"** avisant-te que hi ha algú pendent d'aprovació.
3.  **Activació:** Hauràs d'entrar a la llista d'usuaris, prémer el botó **"Editar"** de la persona nova i canviar el seu estat a **"Aprobado"**. Fins que no ho facis, aquesta persona no podrà veure res del gestor.

---

## 🚪 Baixa del Sistema
Si un usuari ja no treballa al taller o decideix que no vol fer servir més la seva compta, pot anar als seus **Ajustes de Perfil** i prémer el botó **"Borrar mi cuenta definitivamente"**. Això eliminarà el seu accés de forma permanent sense que hagis de fer res tu com a administrador.
