-- Ստեղծում ենք բազա եթե չկա
CREATE DATABASE IF NOT EXISTS qr_animaux CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE qr_animaux;

-- Ստեղծում ենք animals աղյուսակը
CREATE TABLE IF NOT EXISTS animals (
    id INT AUTO_INCREMENT PRIMARY KEY,            -- ID՝ յուրահատուկ կենդանու համար
    name VARCHAR(255) NOT NULL,                   -- Կենդանու անուն
    type VARCHAR(50) NOT NULL,                    -- Տիպ՝ chien/chat/autre
    gender VARCHAR(10) NOT NULL,                  -- Արու/Մորու
    birth_date DATE,                              -- Ծննդյան ամսաթիվ
    color VARCHAR(50),                            -- Գույն
    breed VARCHAR(100),                           -- Ցեղ
    id_chip VARCHAR(100),                         -- Puce / ID
    health_issues TEXT,                           -- Առողջական խնդիրներ
    photos TEXT,                                  -- JSON array ֆայլերի անուններ
    lost_date DATE,                               -- Կորցրած լինելու ամսաթիվ
    additional_info TEXT,                         -- Օգտատիրոջ լրացուցիչ մեկնաբանություններ
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Ստեղծման ամսաթիվ
);