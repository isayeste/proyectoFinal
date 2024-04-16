CREATE DATABASE IF NOT EXISTS psyconnect;
USE psyconnect;

-- PACIENTES
CREATE TABLE pacientes (
    idPaciente INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombrePaciente VARCHAR(100),
    emailPaciente VARCHAR(100),
    contrasenia VARCHAR(100)
);

-- PSICOLOGOS
CREATE TABLE psicologos (
    idPsicologo INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombrePaciente VARCHAR(100),
    emailPaciente VARCHAR(100),
    contrasenia VARCHAR(100),
    especialidad VARCHAR(100) NULL
);

-- HORARIOS
CREATE TABLE horarios (
    idHorario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fecha DATE,
    horaInicio TIME,
    horaFin TIME,
    idPsicologo INT,
    disponible BOOLEAN,
    FOREIGN KEY (idPsicologo) REFERENCES psicologos(idPsicologo) ON DELETE CASCADE
);

-- CITAS
CREATE TABLE citas (
    idCita INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idPaciente INT,
    idPsicologo INT,
    idHorario INT,
    estado ENUM ('solicitada', 'cancelada'),
    modalidad ENUM ('presencial', 'online'),
    FOREIGN KEY (idHorario) REFERENCES horarios(idHorario),
    FOREIGN KEY (idPaciente) REFERENCES pacientes(idPaciente) ON DELETE CASCADE,
    FOREIGN KEY (idPsicologo) REFERENCES psicologos(idPsicologo) ON DELETE CASCADE
);
