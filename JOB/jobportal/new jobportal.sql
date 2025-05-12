-- Drop the database if it already exists
DROP DATABASE IF EXISTS jobportal;

-- Create the database
CREATE DATABASE jobportal;
USE jobportal;

-- Table: Users
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    resume_link VARCHAR(255),
    user_role ENUM('Job Seeker', 'Employer', 'Admin') NOT NULL DEFAULT 'Job Seeker',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Companies
CREATE TABLE Companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    location VARCHAR(255),
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Jobs
CREATE TABLE Jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    job_title VARCHAR(255) NOT NULL,
    job_description TEXT NOT NULL,
    location VARCHAR(255),
    salary DECIMAL(10,2),
    job_type ENUM('Full-Time', 'Part-Time', 'Contract', 'Internship') NOT NULL DEFAULT 'Full-Time',
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES Companies(company_id) ON DELETE CASCADE
);

-- Table: Applications
CREATE TABLE Applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    user_id INT,
    cover_letter TEXT,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    application_status ENUM('Pending', 'Reviewed', 'Accepted', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (job_id) REFERENCES Jobs(job_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Table: Resumes
CREATE TABLE Resumes (
    resume_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    resume_link VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Table: Saved_jobs
CREATE TABLE Saved_jobs (
    saved_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    job_id INT,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES Jobs(job_id) ON DELETE CASCADE
);

-- Insert Sample Data into Users
INSERT INTO Users (full_name, email, password, phone, user_role) VALUES
('John Doe', 'john@example.com', 'hashedpassword123', '9876543210', 'Job Seeker'),
('Jane Smith', 'jane@example.com', 'hashedpassword456', '9876543211', 'Employer');

-- Insert Sample Data into Companies
INSERT INTO Companies (company_name, email, password, phone, location, website) VALUES
('Tech Solutions', 'tech@example.com', 'hashedpassword789', '9876543212', 'New York, USA', 'www.techsolutions.com');

-- Insert Sample Data into Jobs
INSERT INTO Jobs (company_id, job_title, job_description, location, salary, job_type) VALUES
(1, 'Software Engineer', 'Develop web applications', 'Remote', 80000, 'Full-Time'),
(1, 'Data Analyst', 'Analyze business data', 'New York', 70000, 'Full-Time');

-- Insert Sample Data into Applications
INSERT INTO Applications (job_id, user_id, cover_letter) VALUES
(1, 1, 'I am excited to apply for this role.');

-- Insert Sample Data into Resumes
INSERT INTO Resumes (user_id, resume_link) VALUES
(1, 'resumes/john_resume.pdf');

-- Insert Sample Data into Saved_jobs
INSERT INTO Saved_jobs (user_id, job_id) VALUES
(1, 1);

