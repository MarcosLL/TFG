CREATE TABLE Usuarios (
    ID_usu INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    Fecha_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Foto_perfil VARCHAR(255)
);

CREATE TABLE Categorias (
    ID_cat INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE Ubicaciones (
    ID_ubi INT PRIMARY KEY AUTO_INCREMENT,
    Ciudad VARCHAR(100) NOT NULL,
    Pais VARCHAR(100) NOT NULL,
    UNIQUE KEY (Ciudad, Pais)
);

CREATE TABLE Cursos (
    ID_curso INT PRIMARY KEY AUTO_INCREMENT,
    Titulo VARCHAR(255) NOT NULL,
    Descripcion TEXT NOT NULL,
    ID_cat INT NOT NULL,
    Duracion_horas INT NOT NULL,
    Precio DECIMAL(10,2) NOT NULL,
    Imagen_curso VARCHAR(255),
    Fecha_inicio DATE NOT NULL,
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_cat) REFERENCES Categorias(ID_cat)
);

CREATE TABLE Publicaciones (
    ID_publica INT PRIMARY KEY AUTO_INCREMENT,
    ID_curso INT NOT NULL,
    ID_usu INT NOT NULL,
    ID_ubicacion INT NOT NULL,
    Fecha_pub TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_curso) REFERENCES Cursos(ID_curso),
    FOREIGN KEY (ID_usu) REFERENCES Usuarios(ID_usu),
    FOREIGN KEY (ID_ubicacion) REFERENCES Ubicaciones(ID_ubi)
);

CREATE TABLE Inscripciones (
    ID_inscrip INT PRIMARY KEY AUTO_INCREMENT,
    ID_usu INT NOT NULL,
    ID_curso INT NOT NULL,
    Fecha_ins TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (ID_usu) REFERENCES Usuarios(ID_usu),
    FOREIGN KEY (ID_curso) REFERENCES Cursos(ID_curso),
    UNIQUE KEY (ID_usu, ID_curso)
);