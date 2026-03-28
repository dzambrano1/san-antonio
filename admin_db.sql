-- Tabla de administradores
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar admin predeterminado (password: Admin123)
INSERT INTO admin (email, password) VALUES 
('douglasezambrano@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Agregar columnas a la tabla objetos para control de estados y vencimiento
ALTER TABLE objetos ADD COLUMN IF NOT EXISTS estado ENUM('prohibido', 'vencido', 'publicado', 'investigando') DEFAULT 'publicado';
ALTER TABLE objetos ADD COLUMN IF NOT EXISTS fecha_vencimiento DATE NULL;
ALTER TABLE objetos ADD COLUMN IF NOT EXISTS fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Actualizar objetos existentes a 'publicado'
UPDATE objetos SET estado = 'publicado' WHERE estado IS NULL;
