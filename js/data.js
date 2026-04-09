// DATA.JS — Datos estáticos y estado global de la aplicación
// Se carga primero para que func.js y events.js tengan acceso a todas las variables desde el inicio.
// LISTO
// CATÁLOGO DE LIBROS - Cada libro tiene:
// id, título, autor, género, valoracion, lecturas, año, páginas, portada y descripción corta.
const BDLibros = [
      { id: "1", titulo: "Cien años de soledad", autor: "Gabriel García Márquez", genero: "ficcion", valoracion: 4.8, lecturas: 15420, año: 1967, paginas: 471, portada: "📗", descripcion: "La obra maestra del realismo mágico que narra la historia de la familia Buendía en Macondo." },
      { id: "2", titulo: "1984", autor: "George Orwell", genero: "ciencia-ficcion", valoracion: 4.7, lecturas: 12350, año: 1949, paginas: 328, portada: "📕", descripcion: "Una distopía que explora el totalitarismo y la vigilancia masiva." },
      { id: "3", titulo: "El nombre del viento", autor: "Patrick Rothfuss", genero: "fantasia", valoracion: 4.6, lecturas: 9870, año: 2007, paginas: 662, portada: "📘", descripcion: "La historia de Kvothe, un legendario músico y mago." },
      { id: "4", titulo: "Orgullo y prejuicio", autor: "Jane Austen", genero: "romance", valoracion: 4.5, lecturas: 11200, año: 1813, paginas: 432, portada: "📙", descripcion: "Una historia de amor y crítica social en la Inglaterra del siglo XIX." },
      { id: "5", titulo: "El código Da Vinci", autor: "Dan Brown", genero: "misterio", valoracion: 4.2, lecturas: 18900, año: 2003, paginas: 454, portada: "📕", descripcion: "Un thriller que mezcla arte, historia y conspiraciones religiosas." },
      { id: "6", titulo: "Sapiens", autor: "Yuval Noah Harari", genero: "no-ficcion", valoracion: 4.6, lecturas: 14500, año: 2011, paginas: 443, portada: "📗", descripcion: "Una breve historia de la humanidad desde sus orígenes." },
      { id: "7", titulo: "La sombra del viento", autor: "Carlos Ruiz Zafón", genero: "misterio", valoracion: 4.4, lecturas: 10800, año: 2001, paginas: 576, portada: "📘", descripcion: "Un joven descubre un libro misterioso en el Cementerio de los Libros Olvidados." },
      { id: "8", titulo: "Harry Potter y la piedra filosofal", autor: "J.K. Rowling", genero: "fantasia", valoracion: 4.7, lecturas: 25000, año: 1997, paginas: 309, portada: "📙", descripcion: "El inicio de la saga del joven mago más famoso del mundo." },
      { id: "9", titulo: "El principito", autor: "Antoine de Saint-Exupéry", genero: "ficcion", valoracion: 4.8, lecturas: 20100, año: 1943, paginas: 96, portada: "📗", descripcion: "Un cuento filosófico sobre la amistad y el amor." },
      { id: "10", titulo: "Don Quijote de la Mancha", autor: "Miguel de Cervantes", genero: "ficcion", valoracion: 4.5, lecturas: 8900, año: 1605, paginas: 1056, portada: "📕", descripcion: "Las aventuras del ingenioso hidalgo y su fiel escudero." },
      { id: "11", titulo: "Steve Jobs", autor: "Walter Isaacson", genero: "biografia", valoracion: 4.3, lecturas: 7650, año: 2011, paginas: 656, portada: "📘", descripcion: "La biografía autorizada del fundador de Apple." },
      { id: "12", titulo: "Los pilares de la Tierra", autor: "Ken Follett", genero: "historia", valoracion: 4.4, lecturas: 9200, año: 1989, paginas: 1008, portada: "📙", descripcion: "Una épica sobre la construcción de una catedral en la Inglaterra medieval." },
      { id: "13", titulo: "Dune", autor: "Frank Herbert", genero: "ciencia-ficcion", valoracion: 4.5, lecturas: 11000, año: 1965, paginas: 688, portada: "📗", descripcion: "Una saga épica de política, religión y ecología en un planeta desértico." },
      { id: "14", titulo: "Crimen y castigo", autor: "Fiódor Dostoyevski", genero: "ficcion", valoracion: 4.4, lecturas: 7800, año: 1866, paginas: 671, portada: "📕", descripcion: "Un joven estudiante comete un crimen y enfrenta las consecuencias morales." },
      { id: "15", titulo: "El alquimista", autor: "Paulo Coelho", genero: "ficcion", valoracion: 4.2, lecturas: 16500, año: 1988, paginas: 208, portada: "📘", descripcion: "Un pastor andaluz viaja en busca de un tesoro y descubre su leyenda personal." }
    ]; 

// TIENDAS - Usadas en el comparador de precios.
// Los precios reales se generan aleatoriamente en func.js.
const tiendas = [
  { nombre: "Amazon", estrellas: 5, envio: "Envío gratis con Prime" },
  { nombre: "Casa del Libro", estrellas: 5, envio: "Envío gratis +19€" },
  { nombre: "El Corte Inglés", estrellas: 5, envio: "Envío gratis +50€" },
  { nombre: "Fnac", estrellas: 4, envio: "Envío gratis +29€" },
  { nombre: "Iberlibro", estrellas: 4, envio: "Variable por vendedor" }
];

// RESEÑAS DE LA COMUNIDAD - Datos estáticos de ejemplo. Las reseñas del usuario
// se guardan dinámicamente en dataSdk (datosUser).
const resenasComunidad = [
  { idLibro: "1", usuario: "María L.", valoracion: 5, texto: "Una obra maestra absoluta. García Márquez nos transporta a un mundo mágico e inolvidable.", fecha: "2024-01-15" },
  { idLibro: "2", usuario: "Carlos R.", valoracion: 5, texto: "Más relevante que nunca. Una advertencia sobre los peligros del totalitarismo.", fecha: "2024-01-10" },
  { idLibro: "8", usuario: "Ana P.", valoracion: 4, texto: "Perfecto para todas las edades. Me hizo enamorarme de la lectura.", fecha: "2024-01-08" },
  { idLibro: "9", usuario: "Luis M.", valoracion: 5, texto: "Simple pero profundo. Cada relectura revela nuevos significados.", fecha: "2024-01-05" }
];

// TABLA DE USUARIOS TEMPORAL 
const tablaUsuarios = [
  { nombre: "Belen",  password: "123456" },
  { nombre: "Maria", password: "123456" },
  { nombre: "Jessica", password: "123456" },
  { nombre: "Nayeli", password: "123456" }
];

// ESTADO GLOBAL - Variables compartidas entre func.js y events.js.
// Se modifican en respuesta a acciones del usuario o del SDK.
let datosUser = []; // Colección del usuario (sincronizada con dataSdk)
let pestañaActual = 'destacados'; // Pestaña activa
let filtroEstadoActual = 'all'; // Filtro activo en "Mis Libros"
let valoracionSeleccionada = 0; // Valoración seleccionada en el formulario de reseña
let totalLibros = 0; // Número total de libros en la colección (límite: 999)
let usuarioActual = null; // Se llenará con { id, nombre, avatar } desde MySQL

// CONFIGURACIÓN POR DEFECTO - Valores iniciales de personalización visual de la app.
// El usuario puede modificarlos desde el panel de edición.
const configPorDefecto = {
  app_title: "Mi Biblioteca Personal",
  mensaje_bienvenida: "Descubre, organiza y comparte tus lecturas",
  background_color: "#0f0f1a",
  surface_color: "#1a1a2e",
  text_color: "#e8e6e3",
  primary_action: "#f4a261",
  secondary_action: "#2a9d8f",
  font_family: "Source Sans 3",
  font_size: 16
};
