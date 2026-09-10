# FIXTRACK
> Sistema Web de Gestión y Seguimiento de Servicios Técnicos

## ¿De qué trata este proyecto?
FIXTRACK nace para resolver un problema común en los micro y pequeños negocios de reparación de computadoras y videojuegos. Muchas veces, el control de los equipos se lleva en libretas, facturas de papel o chats de WhatsApp, lo que hace difícil darle seguimiento a un trabajo o buscar el historial si un cliente vuelve por una garantía.

La idea de este sistema es organizar y centralizar todo el proceso. Queremos que tanto el técnico como el cliente tengan claro en qué estado está cada equipo, sin la necesidad de estar cruzando llamadas o mensajes constantemente.

## ¿Cómo va a funcionar?
Nos estamos enfocando en mantener las cosas simples, rápidas y directas:
* **IDs únicos para no perder nada:** Vamos a manejar identificadores propios para cada cliente (ej. `C-0142`) y tickets independientes para cada reparación (ej. `A-0052`). Así, si el cliente vuelve tiempo después, su registro sigue intacto y se le asocian sus nuevos tickets.
* **Todo tipo de equipos:** El sistema permite registrar laptops, PCs de escritorio, consolas, controles y periféricos.
* **Seguimiento desde el celular:** El cliente podrá acceder a una interfaz web optimizada para móviles para consultar cómo va su equipo en tiempo real.

## El flujo de los tickets
Cada equipo que ingrese al taller pasará por estos estados para mantener un orden claro:
1. **RECIBIDO**
2. **EN DIAGNÓSTICO**
3. **DIAGNÓSTICO REALIZADO**
4. **PENDIENTE DE AUTORIZACIÓN** (Si el cliente debe aprobar un presupuesto)
5. **EN REPARACIÓN**
6. **LISTO PARA RETIRO**
7. **ENTREGADO**

## Stack Tecnológico y Arquitectura
Para este proyecto decidimos estructurar todo bajo el patrón **MVC (Modelo-Vista-Controlador)**. Las tecnologías y dependencias que vamos a usar son:
* **Backend:** ASP.NET Core MVC (Brinda una separación limpia entre la lógica, la base de datos y la interfaz).
* **Base de Datos / ORM:** SQLite con Entity Framework Core (Adecuado para la etapa de desarrollo y para gestionar de forma sencilla la estructura relacional).
* **Frontend:** HTML5, CSS3 y Bootstrap. Enfoque de diseño adaptado a dispositivos móviles para asegurar una buena experiencia del cliente al revisar su ticket desde el teléfono.