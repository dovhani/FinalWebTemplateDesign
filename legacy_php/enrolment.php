<?php
    // our-school.php - Our School Page
    include 'includes/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolment - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/enrolment.css">
</head>
<body>
    <?php renderNavigation('enrolment'); ?>
    
    <div class="page-header">
        <h1>School Enrolment</h1>
        <p>Join our learning community. Applications for 2024 are now open.</p>
    </div>
    
    <div class="container">
        <!-- Enrolment Process -->
        <div class="content-box enrolment-process">
            <h2>Enrolment Process</h2>
            <p>Follow these simple steps to enroll your child at Learning Academy:</p>
            
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <span class="step-icon">📝</span>
                    <h3>Application</h3>
                    <p>Complete the online application form with required documents.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">2</div>
                    <span class="step-icon">📅</span>
                    <h3>Assessment</h3>
                    <p>Schedule and attend academic assessment (if required).</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">3</div>
                    <span class="step-icon">🤝</span>
                    <h3>Interview</h3>
                    <p>Parent and student interview with school leadership.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">4</div>
                    <span class="step-icon">✅</span>
                    <h3>Acceptance</h3>
                    <p>Receive acceptance letter and enrolment package.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">5</div>
                    <span class="step-icon">💰</span>
                    <h3>Registration</h3>
                    <p>Complete registration and fee payment process.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">6</div>
                    <span class="step-icon">🎒</span>
                    <h3>Orientation</h3>
                    <p>Attend new student orientation before school starts.</p>
                </div>
            </div>
        </div>
        
        <!-- Requirements -->
        <div class="content-box requirements-section">
            <h2>Admission Requirements</h2>
            <p>To enroll at Learning Academy, please ensure you have the following documents ready:</p>
            
            <div class="requirements-grid">
                <div class="requirement-card">
                    <div class="requirement-icon">📄</div>
                    <div class="requirement-content">
                        <h3>Application Form</h3>
                        <p>Completed and signed application form</p>
                    </div>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">👶</div>
                    <div class="requirement-content">
                        <h3>Birth Certificate</h3>
                        <p>Certified copy of learner's birth certificate</p>
                    </div>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">🆔</div>
                    <div class="requirement-content">
                        <h3>ID Documents</h3>
                        <p>Copies of parents/guardians' ID documents</p>
                    </div>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">🏠</div>
                    <div class="requirement-content">
                        <h3>Proof of Residence</h3>
                        <p>Utility bill or lease agreement (not older than 3 months)</p>
                    </div>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">📚</div>
                    <div class="requirement-content">
                        <h3>Academic Records</h3>
                        <p>Latest school report and transfer card (if applicable)</p>
                    </div>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">💉</div>
                    <div class="requirement-content">
                        <h3>Medical Information</h3>
                        <p>Immunization records and medical aid details</p>
                    </div>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">📸</div>
                    <div class="requirement-content">
                        <h3>Photographs</h3>
                        <p>4 recent passport-sized photographs of learner</p>
                    </div>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">📝</div>
                    <div class="requirement-content">
                        <h3>Additional Forms</h3>
                        <p>Completed medical and indemnity forms</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Application Form -->
        <div class="content-box application-form-section">
            <h2>Online Application Form</h2>
            <p>Complete the form below to start your child's enrolment process.</p>
            
            <form class="enrolment-form" id="enrolmentForm">
                <div class="form-section">
                    <h3>Student Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="student-name">Full Name *</label>
                            <input type="text" id="student-name" placeholder="Student's full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date-of-birth">Date of Birth *</label>
                            <input type="date" id="date-of-birth" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="grade-applying">Grade Applying For *</label>
                            <select id="grade-applying" required>
                                <option value="">Select Grade</option>
                                <option value="grade-r">Grade R</option>
                                <option value="grade-1">Grade 1</option>
                                <option value="grade-2">Grade 2</option>
                                <option value="grade-3">Grade 3</option>
                                <option value="grade-4">Grade 4</option>
                                <option value="grade-5">Grade 5</option>
                                <option value="grade-6">Grade 6</option>
                                <option value="grade-7">Grade 7</option>
                                <option value="grade-8">Grade 8</option>
                                <option value="grade-9">Grade 9</option>
                                <option value="grade-10">Grade 10</option>
                                <option value="grade-11">Grade 11</option>
                                <option value="grade-12">Grade 12</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="current-school">Current School</label>
                            <input type="text" id="current-school" placeholder="If applicable">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Parent/Guardian Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parent-name">Parent/Guardian Name *</label>
                            <input type="text" id="parent-name" placeholder="Full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="relationship">Relationship to Student *</label>
                            <select id="relationship" required>
                                <option value="">Select Relationship</option>
                                <option value="mother">Mother</option>
                                <option value="father">Father</option>
                                <option value="guardian">Guardian</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parent-email">Email Address *</label>
                            <input type="email" id="parent-email" placeholder="Your email address" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="parent-phone">Phone Number *</label>
                            <input type="tel" id="parent-phone" placeholder="Your phone number" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="home-address">Home Address *</label>
                        <textarea id="home-address" placeholder="Full residential address" rows="3" required></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Additional Information</h3>
                    
                    <div class="form-group">
                        <label for="medical-conditions">Medical Conditions/Allergies</label>
                        <textarea id="medical-conditions" placeholder="Please list any medical conditions, allergies, or special needs" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="previous-academic">Previous Academic Performance</label>
                        <textarea id="previous-academic" placeholder="Briefly describe previous academic performance, strengths, and areas for development" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="extracurricular">Extracurricular Interests</label>
                        <textarea id="extracurricular" placeholder="Sports, arts, clubs, or other interests" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>How did you hear about us?</label>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="hear-about" value="friend"> Friend/Family</label>
                            <label><input type="checkbox" name="hear-about" value="website"> Website</label>
                            <label><input type="checkbox" name="hear-about" value="social"> Social Media</label>
                            <label><input type="checkbox" name="hear-about" value="advertisement"> Advertisement</label>
                            <label><input type="checkbox" name="hear-about" value="other"> Other</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Document Upload</h3>
                    <p>You will need to submit the following documents. You can upload them now or bring them to the school.</p>
                    
                    <div class="document-upload">
                        <div class="upload-item">
                            <label for="birth-certificate">Birth Certificate</label>
                            <input type="file" id="birth-certificate" accept=".pdf,.jpg,.png">
                        </div>
                        
                        <div class="upload-item">
                            <label for="id-copy">ID Document Copy</label>
                            <input type="file" id="id-copy" accept=".pdf,.jpg,.png">
                        </div>
                        
                        <div class="upload-item">
                            <label for="report-card">Latest Report Card</label>
                            <input type="file" id="report-card" accept=".pdf,.jpg,.png">
                        </div>
                        
                        <div class="upload-item">
                            <label for="proof-residence">Proof of Residence</label>
                            <input type="file" id="proof-residence" accept=".pdf,.jpg,.png">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" required>
                            I confirm that all information provided is accurate and complete *
                        </label>
                    </div>
                    
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" required>
                            I agree to the school's terms and conditions *
                        </label>
                    </div>
                    
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox">
                            I would like to receive updates about school events and news
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn">Submit Application</button>
                    <button type="reset" class="btn btn-secondary">Reset Form</button>
                </div>
                
                <p class="form-note">* Required fields. You will receive a confirmation email within 24 hours.</p>
            </form>
        </div>
        
        <!-- Fees Section -->
        <div class="content-box fees-section">
            <h2>School Fees 2024</h2>
            <p>Our fee structure is transparent and includes all essential educational costs. Financial assistance is available for qualifying families.</p>
            
            <div class="fees-table-container">
                <table class="fees-table">
                    <thead>
                        <tr>
                            <th>Grade Level</th>
                            <th>Annual Tuition</th>
                            <th>Registration Fee</th>
                            <th>Monthly Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Grade R-3</td>
                            <td>R 35,000</td>
                            <td>R 2,500</td>
                            <td>R 3,500</td>
                        </tr>
                        <tr>
                            <td>Grade 4-7</td>
                            <td>R 42,000</td>
                            <td>R 2,500</td>
                            <td>R 4,200</td>
                        </tr>
                        <tr>
                            <td>Grade 8-9</td>
                            <td>R 48,000</td>
                            <td>R 3,000</td>
                            <td>R 4,800</td>
                        </tr>
                        <tr>
                            <td>Grade 10-12</td>
                            <td>R 55,000</td>
                            <td>R 3,000</td>
                            <td>R 5,500</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="fees-note">
                <p><strong>Note:</strong> Fees include tuition, textbooks, stationery, and most extracurricular activities. Additional costs may apply for optional activities, trips, and specialized equipment.</p>
                <p>Discounts available for siblings and early payment. Financial assistance applications close on October 31, 2023.</p>
                <a href="contact.php" class="btn">Apply for Financial Assistance</a>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="content-box faq-section">
            <h2>Frequently Asked Questions</h2>
            
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What is the application deadline for 2024?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Applications for the 2024 academic year are accepted until November 30, 2023. Late applications may be considered if spaces are available.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>When will I know if my child has been accepted?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Acceptance letters are typically sent within 2 weeks of completing the application and assessment process. For late applications, decisions are made within 5 working days.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Are there any scholarships or bursaries available?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we offer academic, sports, and arts scholarships as well as need-based financial assistance. Applications for scholarships close on October 31, 2023.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What is the school's language policy?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>English is the medium of instruction. We offer Afrikaans and isiZulu as additional language options. Support is available for English second language learners.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Does the school offer boarding facilities?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Currently, we do not offer boarding facilities. We are a day school only, with a comprehensive aftercare program available until 5:30 PM.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What are the school hours?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>School starts at 7:30 AM and ends at 2:30 PM. Aftercare is available until 5:30 PM. Extracurricular activities are scheduled after school hours.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Admissions -->
        <div class="content-box contact-admissions">
            <h2>Contact Admissions Office</h2>
            <p>For any questions about the enrolment process, please contact our admissions team:</p>
            
            <div class="contact-info-cards">
                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <h3>Phone</h3>
                    <p>(012) 345-6789 ext. 101</p>
                    <p>Mon-Fri: 8:00 AM - 4:00 PM</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">✉️</div>
                    <h3>Email</h3>
                    <p>admissions@learningacademy.edu</p>
                    <p>Response within 24 hours</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">📍</div>
                    <h3>Visit Us</h3>
                    <p>Admissions Office</p>
                    <p>Main Administration Building</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">📅</div>
                    <h3>Open Days</h3>
                    <p>Monthly School Tours</p>
                    <a href="calendar-events.php" class="btn btn-secondary">Schedule Tour</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        include 'includes/footer.php';
        renderFooter(); 
    ?>    
    <script src="js/main.js"></script>
    
    <script>
        // FAQ Toggle Function
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('.faq-icon');
            
            answer.classList.toggle('active');
            icon.textContent = answer.classList.contains('active') ? '−' : '+';
        }
        
        // Form Submission
        document.getElementById('enrolmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your application! We will contact you within 24 hours to schedule the next steps.');
            this.reset();
        });
    </script>
</body>
</html>