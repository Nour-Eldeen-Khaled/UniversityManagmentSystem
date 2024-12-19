-- Insert sample users (including the admin we already have)
INSERT INTO users (first_name, last_name, email, password, role) VALUES
-- Professors
('John', 'Smith', 'john.smith@university.com', 'password', 'professor'),
('Sarah', 'Johnson', 'sarah.johnson@university.com', 'password', 'professor'),
('Michael', 'Brown', 'michael.brown@university.com', 'password', 'professor'),
-- Students
('Emily', 'Davis', 'emily.davis@university.com', 'password', 'student'),
('James', 'Wilson', 'james.wilson@university.com', 'password', 'student'),
('Emma', 'Taylor', 'emma.taylor@university.com', 'password', 'student'),
('Daniel', 'Anderson', 'daniel.anderson@university.com', 'password', 'student'),
('Sophia', 'Martinez', 'sophia.martinez@university.com', 'password', 'student');

-- Insert courses (assigned to professors)
INSERT INTO courses (course_code, title, description, credits, professor_id) VALUES
('CS101', 'Introduction to Computer Science', 'Fundamental concepts of programming and computer science', 3, 
    (SELECT id FROM users WHERE email = 'john.smith@university.com')),
('MATH201', 'Advanced Mathematics', 'Advanced concepts in calculus and linear algebra', 4,
    (SELECT id FROM users WHERE email = 'sarah.johnson@university.com')),
('PHY301', 'Physics III', 'Advanced physics concepts including quantum mechanics', 4,
    (SELECT id FROM users WHERE email = 'michael.brown@university.com')),
('CS202', 'Data Structures', 'Advanced programming concepts and data structures', 3,
    (SELECT id FROM users WHERE email = 'john.smith@university.com')),
('MATH102', 'Statistics', 'Introduction to statistical analysis and probability', 3,
    (SELECT id FROM users WHERE email = 'sarah.johnson@university.com'));

-- Insert enrollments
INSERT INTO enrollments (student_id, course_id, status) VALUES
-- Emily Davis's enrollments
((SELECT id FROM users WHERE email = 'emily.davis@university.com'),
 (SELECT id FROM courses WHERE course_code = 'CS101'), 'active'),
((SELECT id FROM users WHERE email = 'emily.davis@university.com'),
 (SELECT id FROM courses WHERE course_code = 'MATH201'), 'active'),

-- James Wilson's enrollments
((SELECT id FROM users WHERE email = 'james.wilson@university.com'),
 (SELECT id FROM courses WHERE course_code = 'CS101'), 'active'),
((SELECT id FROM users WHERE email = 'james.wilson@university.com'),
 (SELECT id FROM courses WHERE course_code = 'PHY301'), 'active'),

-- Emma Taylor's enrollments
((SELECT id FROM users WHERE email = 'emma.taylor@university.com'),
 (SELECT id FROM courses WHERE course_code = 'MATH201'), 'active'),
((SELECT id FROM users WHERE email = 'emma.taylor@university.com'),
 (SELECT id FROM courses WHERE course_code = 'CS202'), 'dropped'),

-- Daniel Anderson's enrollments
((SELECT id FROM users WHERE email = 'daniel.anderson@university.com'),
 (SELECT id FROM courses WHERE course_code = 'PHY301'), 'active'),
((SELECT id FROM users WHERE email = 'daniel.anderson@university.com'),
 (SELECT id FROM courses WHERE course_code = 'MATH102'), 'active');

-- Insert grades for completed courses
INSERT INTO grades (enrollment_id, grade, comments, graded_by) VALUES
-- Emily Davis's grades
((SELECT e.id FROM enrollments e
  JOIN users u ON e.student_id = u.id
  JOIN courses c ON e.course_id = c.id
  WHERE u.email = 'emily.davis@university.com' AND c.course_code = 'CS101'),
 92.5, 'Excellent work throughout the semester',
 (SELECT id FROM users WHERE email = 'john.smith@university.com')),

-- James Wilson's grades
((SELECT e.id FROM enrollments e
  JOIN users u ON e.student_id = u.id
  JOIN courses c ON e.course_id = c.id
  WHERE u.email = 'james.wilson@university.com' AND c.course_code = 'CS101'),
 88.0, 'Good performance in all assignments',
 (SELECT id FROM users WHERE email = 'john.smith@university.com')),

-- Emma Taylor's grades
((SELECT e.id FROM enrollments e
  JOIN users u ON e.student_id = u.id
  JOIN courses c ON e.course_id = c.id
  WHERE u.email = 'emma.taylor@university.com' AND c.course_code = 'MATH201'),
 95.0, 'Outstanding performance',
 (SELECT id FROM users WHERE email = 'sarah.johnson@university.com')),

-- Daniel Anderson's grades
((SELECT e.id FROM enrollments e
  JOIN users u ON e.student_id = u.id
  JOIN courses c ON e.course_id = c.id
  WHERE u.email = 'daniel.anderson@university.com' AND c.course_code = 'PHY301'),
 87.5, 'Good understanding of concepts',
 (SELECT id FROM users WHERE email = 'michael.brown@university.com'));

-- Insert course materials
INSERT INTO course_materials (course_id, title, description, file_path, uploaded_by) VALUES
-- CS101 materials
((SELECT id FROM courses WHERE course_code = 'CS101'),
 'Introduction to Programming', 'Basic programming concepts and syntax',
 'materials/cs101/intro_programming.pdf',
 (SELECT id FROM users WHERE email = 'john.smith@university.com')),

-- MATH201 materials
((SELECT id FROM courses WHERE course_code = 'MATH201'),
 'Calculus Review', 'Review of fundamental calculus concepts',
 'materials/math201/calculus_review.pdf',
 (SELECT id FROM users WHERE email = 'sarah.johnson@university.com')),

-- PHY301 materials
((SELECT id FROM courses WHERE course_code = 'PHY301'),
 'Quantum Mechanics Basics', 'Introduction to quantum mechanics principles',
 'materials/phy301/quantum_basics.pdf',
 (SELECT id FROM users WHERE email = 'michael.brown@university.com')),

-- CS202 materials
((SELECT id FROM courses WHERE course_code = 'CS202'),
 'Data Structures Overview', 'Overview of basic data structures',
 'materials/cs202/data_structures.pdf',
 (SELECT id FROM users WHERE email = 'john.smith@university.com')),

-- MATH102 materials
((SELECT id FROM courses WHERE course_code = 'MATH102'),
 'Probability Fundamentals', 'Basic concepts of probability',
 'materials/math102/probability_basics.pdf',
 (SELECT id FROM users WHERE email = 'sarah.johnson@university.com'));

 -- Insert contact_us 
 -- Insert multiple sample messages into the contact_us table
INSERT INTO contact_us (user_id, subject, message) VALUES
((SELECT id FROM users WHERE email = 'emily.davis@university.com'), 
'Question about course schedule', 
'Could you please confirm the course schedule for the upcoming semester? I need to plan my classes accordingly.'),
((SELECT id FROM users WHERE email = 'james.wilson@university.com'), 
'Inquiry about CS101 Materials', 
'Can you provide more details about the course materials for Introduction to Computer Science? I would like to understand the prerequisites better.'),
((SELECT id FROM users WHERE email = 'emma.taylor@university.com'), 
'Feedback on MATH201', 
'I wanted to share some feedback on the MATH201 course. The lectures were great, but I found the assignments challenging.'),
((SELECT id FROM users WHERE email = 'daniel.anderson@university.com'), 
'Question on PHY301 syllabus', 
'Can you clarify some points on the PHY301 syllabus? I’m not sure about the topics covered in the final exam.'),
((SELECT id FROM users WHERE email = 'sophia.martinez@university.com'), 
'Request for TA assistant', 
'Could I request a teaching assistant for CS202? I’m struggling with the course materials and need additional help.');


-- Note: All passwords are set to 'password' using the same hash
