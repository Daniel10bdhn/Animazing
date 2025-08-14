-- Tabla usuarios
CREATE TABLE usuarios (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(191) NOT NULL,
    correo_electronico VARCHAR(191) UNIQUE NOT NULL,
    correo_verificado TIMESTAMP NULL,
    contraseña VARCHAR(100) NOT NULL,
    recordar_token VARCHAR(191) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla roles
CREATE TABLE roles (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(191) NOT NULL,
    nombre_guardado VARCHAR(191) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    descripcion VARCHAR(191)
) ENGINE=InnoDB;

-- Tabla pivote rol_usuario
CREATE TABLE rol_usuario (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rol_id BIGINT(20) UNSIGNED NOT NULL,
    usuario_id BIGINT(20) UNSIGNED NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla animales
CREATE TABLE animales (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(191) NOT NULL,
    sexo VARCHAR(191) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla fundaciones
CREATE TABLE fundaciones (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(191) NOT NULL,
    teléfono VARCHAR(191),
    dirección VARCHAR(191),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuario_id BIGINT(20) UNSIGNED,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabla razas
CREATE TABLE razas (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(191) NOT NULL,
    eliminado_en TIMESTAMP NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla mascotas
CREATE TABLE mascotas (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT(20) UNSIGNED NOT NULL,
    raza_id BIGINT(20) UNSIGNED NOT NULL,
    nombre VARCHAR(191) NOT NULL,
    foto VARCHAR(191),
    descripcion TEXT,
    estado ENUM('PUBLICADO', 'BORRADOR') DEFAULT 'BORRADOR',
    eliminado_en TIMESTAMP NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (raza_id) REFERENCES razas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla adopciones
CREATE TABLE adopciones (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha_adopcion DATETIME NOT NULL,
    usuario_id BIGINT(20) UNSIGNED NOT NULL,
    animal_id BIGINT(20) UNSIGNED NOT NULL,
    estado VARCHAR(191) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES animales(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla favoritos
CREATE TABLE favoritos (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    animal_id BIGINT(20) UNSIGNED NOT NULL,
    usuario_id BIGINT(20) UNSIGNED NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animales(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insertar usuarios
INSERT INTO usuarios (nombre, correo_electronico, contraseña) VALUES
('Juan Pérez', 'juan.perez1@example.com', '123456'),
('María Gómez', 'maria.gomez@example.com', 'abcdef'),
('sebastian guzman', 'sebastianguzman266@gmail.com', 'Sebas123');


-- Insertar roles
INSERT INTO roles (nombre, nombre_guardado, descripcion) VALUES
('Administrador', 'admin', 'Acceso total al sistema'),
('Fundación', 'foundation', 'Gestión de animales y adopciones'),
('Usuario', 'user', 'Usuario que puede adoptar');

-- Asignar roles a usuarios
INSERT INTO rol_usuario (rol_id, usuario_id) VALUES
(1, 1), -- Juan es Administrador
(2, 2), -- María es Fundación
(3, 3); -- Carlos es Usuario

-- Insertar animales
INSERT INTO animales (nombre, sexo) VALUES
('Firulais', 'Macho'),
('Luna', 'Hembra'),
('Max', 'Macho'),
('Rocky', 'Macho'),
('Bella', 'Hembra'),
('Toby', 'Macho'),
('Simba', 'Macho'),
('Nala', 'Hembra'),
('Coco', 'Macho'),
('Daisy', 'Hembra'),
('Rex', 'Macho'),
('Milo', 'Macho'),
('Lola', 'Hembra');

-- Insertar razas
INSERT INTO razas (nombre) VALUES
('Labrador Retriever'),
('Pastor Alemán'),
('Bulldog Francés'),
('Golden Retriever'),
('Beagle'),
('Pug'),
('Boxer'),
('Rottweiler'),
('Chihuahua'),
('Dálmata'),
('Husky Siberiano'),
('Shih Tzu'),
('Mestizo');



-- Insertar mascotas
INSERT INTO mascotas (usuario_id, raza_id, nombre, foto, descripcion, estado) VALUES
(2, 1, 'Firulais', 'firulais.jpg', 'Perro juguetón y amigable', 'PUBLICADO'),
(2, 2, 'Luna', 'luna.jpg', 'Perra muy cariñosa', 'PUBLICADO'),
(2, 3, 'Max', 'max.jpg', 'Perro protector y leal', 'BORRADOR'),
(2,4, 'Rocky', 'rocky.jpg', 'Perro alegre y lleno de energía', 'PUBLICADO'),
(2,5, 'Bella', 'bella.jpg', 'Perra tranquila y obediente', 'PUBLICADO'),
(2,6, 'Toby', 'toby.jpg', 'Muy juguetón, ideal para familias con niños', 'PUBLICADO'),
(2,7, 'Simba', 'simba.jpg', 'Perro curioso y cariñoso', 'BORRADOR'),
(2,8, 'Nala', 'nala.jpg', 'Perrita dulce que adora dormir al sol', 'PUBLICADO'),
(2,9, 'Coco', 'coco.jpg', 'Perrito parlanchín y amistoso', 'BORRADOR'),
(2,10, 'Daisy', 'daisy.jpg', 'Perrita tranquila, le encanta comer zanahorias', 'PUBLICADO'),
(2,11, 'Rex', 'rex.jpg', 'Perro guardián muy leal', 'BORRADOR'),
(2,12, 'Milo', 'milo.jpg','Cachorro travieso y muy juguetón', 'PUBLICADO'),
(2,13, 'Lola', 'lola.jpg', 'Perra muy tierna y amigable con otros animales', 'BORRADOR');

-- Insertar fundaciones
INSERT INTO fundaciones (nombre, teléfono, dirección, usuario_id) VALUES
('Fundación Patitas Felices', '3001234567', 'Calle 10 #5-20', 2);

-- Insertar adopciones
INSERT INTO adopciones (fecha_adopcion, usuario_id, animal_id, estado) VALUES
('2025-01-10 10:30:00', 3, 1, 'Completada'),
('2025-02-05 14:00:00', 3, 2, 'En proceso');

-- Insertar favoritos
INSERT INTO favoritos (animal_id, usuario_id) VALUES
(1, 3),
(2, 3);




