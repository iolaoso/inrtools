    -- Instrucciones para vaciar la base de datos y volver a crearla
    DROP DATABASE IF EXISTS INRtools; -- Eliminar la base de datos si existe
    CREATE DATABASE INRtools CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci; -- Crear la base de datos
    USE INRtools; -- Usar la base de datos creada

    -- Tabla de direcciones
    CREATE TABLE inrdireccion (
        id INT AUTO_INCREMENT PRIMARY KEY,
        direccion VARCHAR(255) NOT NULL,
        dirNombre VARCHAR(100) NOT NULL,
        estRegistro TINYINT DEFAULT 1, -- 1: Activo, 0: Inactivo
        UsrCreacion VARCHAR(50) NOT NULL,
        deletedAt TIMESTAMP NULL DEFAULT NULL,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

    -- Tabla de roles
    CREATE TABLE roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL UNIQUE,
        descripcion TEXT,
        estRegistro TINYINT DEFAULT 1, -- 1: Activo, 0: Inactivo
        UsrCreacion VARCHAR(50) NOT NULL,
        deletedAt TIMESTAMP NULL DEFAULT NULL,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

    -- Tabla de personas (modificada)
    CREATE TABLE personas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        identificacion VARCHAR(50) NOT NULL UNIQUE,
        nombre VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        inrdireccion_id INT,
        estRegistro TINYINT DEFAULT 1, -- 1: Activo, 0: Inactivo
        UsrCreacion VARCHAR(50) NOT NULL,
        deletedAt TIMESTAMP NULL DEFAULT NULL,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (inrdireccion_id) REFERENCES inrdireccion(id) ON DELETE SET NULL
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

    -- Tabla de usuarios
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        persona_id INT NOT NULL,
        nickname VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        estRegistro TINYINT DEFAULT 1, -- 1: Activo, 0: Inactivo
        UsrCreacion VARCHAR(50) NOT NULL,
        deletedAt TIMESTAMP NULL DEFAULT NULL,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

    -- Tabla de roles de usuarios
    CREATE TABLE user_roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        rol_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
        UNIQUE (user_id, rol_id) -- Combinación única
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;


    CREATE TABLE estructuras (
        id INT AUTO_INCREMENT PRIMARY KEY,
        solicitante VARCHAR(50) NOT NULL,
        direccion_solicitante VARCHAR(100) NOT NULL, -- Cambiado a direccion_solicitante
        ruc VARCHAR(100) NOT NULL,
        estructura VARCHAR(100) NOT NULL,
        fechaCorte DATE NOT NULL,
        fecha_solicitud DATE NOT NULL,
        estado VARCHAR(20) NOT NULL, -- Tipo VARCHAR
        analista_ejecutante VARCHAR(50),
        estRegistro TINYINT DEFAULT 1, -- 1: Activo, 0: Inactivo
        UsrCreacion VARCHAR(50) NOT NULL,
        deletedAt TIMESTAMP NULL DEFAULT NULL,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

    CREATE TABLE reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(100) NOT NULL UNIQUE,
    expiracion DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_expiracion (expiracion),
    INDEX idx_usado (usado),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

    SET foreign_key_checks = 0;
    -- inserción de datos

    -- Insertar direcciones
    INSERT INTO inrdireccion (direccion, dirNombre, estRegistro, UsrCreacion) VALUES 
    ('INR', 'Intendencia Nacional de Riesgos', 1, 'ILOPEZ'),
    ('DNR', 'Dirección Nacional de Riesgos', 1, 'ILOPEZ'),
    ('DNSES', 'Dirección Nacional de Supervisión Extra Situ', 1, 'ILOPEZ'),
    ('DNS', 'Dirección Nacional de Supervisión', 1, 'ILOPEZ'),
    ('DNPLA', 'Dirección Nacional de Prevención de Lavado de Activos', 1, 'ILOPEZ');

    -- Insertar roles
    INSERT INTO roles (nombre, descripcion, estRegistro, UsrCreacion) VALUES 
    ('SUPERUSER', 'Acceso completo a todas las funcionalidades. FULL', 1, 'ILOPEZ'),
    ('ANALISTA', 'Acceso limitado a funciones específicas de análisis.', 1, 'ILOPEZ'),
    ('ADMINISTRADOR', 'Acceso limitado a la mayoria de funciones y configuraciónn', 1, 'ILOPEZ'),
    ('CONSULTA', 'solamente a los reportes específicos', 1, 'ILOPEZ'),
    ('DIRECTOR', 'Secciones especificas para directores', 1, 'ILOPEZ');
    


    -- Insertar personas (modificado)
    INSERT INTO personas (id,identificacion,nombre,email,inrdireccion_id,estRegistro,UsrCreacion,createdAt,updatedAt) VALUES 
    ('1','1803223922','ISRAEL LOPEZ','israel.lopez@seps.gob.ec','2','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('2','0401545264','GLENDA BENAVIDES','glenda.benavides@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('3','1712769304','JORGE BALLADARES','jorge.balladares@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('4','1714409040','DAVID CEVALLOS','david.cevallos@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('5','1717479636','CARLA CORRALES','carla.corrales@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('6','1718859059','MONICA ERAZO ','monica.erazo.@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('7','1720231230','DIANA PAGUAY','diana.paguay@seps.gob.ec','2','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('9','1714894126','MARCELO JARA','marcelo.jara@seps.gob.ec','5','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('10','1716060726','LILIANA LOPEZ','liliana.lopez@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('11','1803889789','GABRIELA IBARRA','gabriela.ibarra@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('12','1714917745','ESTEBAN VACA','esteban.vaca@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('15','1707168827','YOMARA LUZURIAGA','yomara.luzuriaga@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('16','1721113643','DIANA NARANJO','diana.naranjo@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('18','0401592134','DIEGO TARAPUES','diego.tarapues@seps.gob.ec','5','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('19','1103324180','OMAYA OCAMPO','omaya.ocampo@seps.gob.ec','5','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('20','1704425683','GALO LUCIO','galo.lucio@seps.gob.ec','2','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('21','1714006549','MARIA ALMEIDA','maria.almeida@seps.gob.ec','5','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('22','1717700890','RICHARD SOTO','richard.soto@seps.gob.ec','2','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('24','1803347085','EDISSON ACOSTA','edisson.acosta@seps.gob.ec','2','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('26','0201779824','FRED BORJA','fred.borja@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('27','0604418913','RODRIGO MORAN','rodrigo.moran@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('28','1104569171','ROSA LOPEZ','rosa.lopez@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('31','1713708574','ALFREDO GALARZA','alfredo.galarza@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('32','1715408819','ANA BRICEÑO','ana.briceño@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('33','1716305147','ANDREA CHANCHAY','andrea.chanchay@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('34','1718413915','TATIANA ALOMOTO','tatiana.alomoto@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('35','1723594873','GISSELA TRUJILLO','gissela.trujillo@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('36','1803561875','CARMIÑA PAREDES','carmiña.paredes@seps.gob.ec','4','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44'),
    ('38','1717633125','MARITZA LLONGO','maritza.llongo@seps.gob.ec','3','1','ILOPEZ','2025-02-18 09:25:44','2025-02-18 09:25:44');


    -- Insertar usuarios -- Contraseña: 123456789
    INSERT INTO users (id,persona_id, nickname, password, estRegistro, UsrCreacion,createdAt,updatedAt) VALUES 
    ('1','1','ILOPEZ','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('2','2','GBENAVIDES','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('3','3','JBALLADARES','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('4','4','DCEVALLOS','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('5','5','CCORRALES','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('6','6','MERAZO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('7','7','DPAGUAY','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('8','9','MJARA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('9','10','LLOPEZ','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('10','11','GIBARRA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('11','12','EVACA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('12','15','NLUZURIAGA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('13','16','DNARANJO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('14','18','DTARAPUES','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('15','19','OOCAMPO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('16','20','GLUCIO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('17','21','MALMEIDA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('18','22','RSOTO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('19','24','EACOSTA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('20','26','FBORJA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('21','27','RMORAN','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('22','28','RLOPEZ','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('23','31','AGALARZA','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('24','32','ABRICEÑO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('25','33','ACHANCHAY','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('26','34','TALOMOTO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('27','35','GTRUJILLO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('28','36','CPAREDES','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44'),
    ('29','38','MLLONGO','$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu','1','ILOPEZ','2025-02-18 09:30:44','2025-02-18 09:30:44');



    -- Asignar roles a usuarios --ILOPEZ tiene rol de superuser 1
    INSERT INTO user_roles (user_id, rol_id) VALUES 
    ('1','1'),
    ('2','2'),
    ('3','2'),
    ('4','2'),
    ('5','2'),
    ('6','2'),
    ('7','2'),
    ('8','2'),
    ('9','2'),
    ('10','2'),
    ('11','2'),
    ('12','2'),
    ('13','2'),
    ('14','2'),
    ('15','2'),
    ('16','2'),
    ('17','2'),
    ('18','2'),
    ('19','2'),
    ('20','2'),
    ('21','2'),
    ('22','2'),
    ('23','2'),
    ('24','2'),
    ('25','2'),
    ('26','2'),
    ('27','2'),
    ('28','2'),
    ('29','2');


    -- EJEMPLO INSERT ESTRUCTURAS
    --INSERT INTO estructuras (solicitante, direccion_solicitante, ruc, estructura, fechaCorte, fecha_solicitud, estado, analista_ejecutante, UsrCreacion)
    --VALUES 
    --('GLENDA BENAVIDES', 'DNSES', '1891726712001', 'CARTERA C01-C02', '2024-12-31', '2025-01-06', 'ENVIADO', 'ILOPEZ', 'ILOPEZ'),
    
    SET foreign_key_checks = 1;