# 4.3. REQUERIMENTS NO FUNCIONALS

Aquest apartat defineix les restriccions i els atributs de qualitat que garanteixen que el sistema GestorTaller sigui robust, segur i fàcil de mantenir.

## 1. SEGURETAT I INTEGRITAT
- **Protecció de dades**: Totes les dades sensibles (especialment contrasenyes) s'han d'emmagatzemar mitjançant hashing `BCrypt`.
- **Prevenció de vulnerabilitats**: El sistema ha d'estar protegit contra els atacs web més comuns: SQL Injection, Cross-Site Scripting (XSS) i Cross-Site Request Forgery (CSRF).
- **Control d'accés**: Només els usuaris degudament autenticats i aprovats per l'administrador poden accedir a les funcionalitats internes de gestió.

## 2. USABILITAT I DISSENY
- **Interfície Intuïtiva**: El disseny ha de permetre a l'operari realitzar les tasques habituals (crear un encàrrec o pujar una foto) amb un màxim de 3 clics des del menú principal.
- **Disseny Responsive (Adaptabilitat)**: L'aplicació ha de ser 100% funcional en dispositius mòbils i tablets, ja que el personal del taller necessita mobilitat mentre treballa amb els vehicles.
- **Experiència d'Usuari (UX)**: Ús de feedback visual (toasts de confirmació i alertes de colors) per confirmar que les accions s'han realitzat correctament.

## 3. RENDIMENT I ESCALABILITAT
- **Temps de resposta**: El temps de càrrega de les taules i els taulells Kanban no ha de superar els 2 segons en condicions normals de xarxa.
- **Arquitectura Modular**: El sistema ha d'estar construït de forma modular (seguint el patró MVC de Laravel) per permetre l'addició de noves funcionalitats en el futur sense afectar el nucli del programa.
- **Gestió d'Assets**: Ús de Vite per a la compilació i optimització d'estils i scripts, garantint un lliurament ràpid dels recursos al navegador.

## 4. COMPATIBILITAT I DISPONIBILITAT
- **Multiplataforma**: El sistema ha de ser compatible amb els navegadors moderns més utilitzats (Google Chrome, Mozilla Firefox, Safari i Microsoft Edge).
- **Accessibilitat URL**: L'aplicació ha d'estar allotjada en un servidor web que garanteixi una disponibilitat mínima del 99% per assegurar l'accés durant la jornada laboral del taller.

## 5. MANTENIBILITAT
- **Documentació de codi**: El codi ha de seguir els estàndards PSR de PHP i incloure comentaris descriptius en les funcions més complexes.
- **Control de versions**: Tota l'evolució del projecte ha d'estar registrada mitjançant Git, permetent la reversió de canvis en cas d'error.
- **Gestió d'errors**: Implementació d'un sistema de logs i visualització d'errors personalitzada per evitar mostrar informació tècnica sensible a l'usuari final.
