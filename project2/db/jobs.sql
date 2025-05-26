-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 24, 2025 at 05:45 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobs`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `JobReferenceNumber` varchar(5) NOT NULL,
  `FirstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL,
  `StreetAddress` varchar(40) NOT NULL,
  `Suburb` varchar(40) NOT NULL,
  `State` enum('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  `Postcode` char(4) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `OtherSkills` text DEFAULT NULL,
  `ResumePath` varchar(255) DEFAULT NULL,
  `Status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eoi_skills`
--

CREATE TABLE `eoi_skills` (
  `EOInumber` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_listings`
--

CREATE TABLE `job_listings` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `reference_code` varchar(20) NOT NULL,
  `reports_to` varchar(100) NOT NULL,
  `salary_range` varchar(50) NOT NULL,
  `overview` text NOT NULL,
  `responsibilities` text NOT NULL,
  `essential_qualifications` text NOT NULL,
  `preferable_qualifications` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_listings`
--

INSERT INTO `job_listings` (`id`, `title`, `reference_code`, `reports_to`, `salary_range`, `overview`, `responsibilities`, `essential_qualifications`, `preferable_qualifications`) VALUES
(1, 'Software Developer', 'G01', 'Software Development Manager', 'AUD $90,000 - $120,000', 'We are seeking a talented Software Developer to join our growing development team in Australia. You will be responsible for building and maintaining scalable software solutions and collaborating with cross-functional teams to deliver high-quality applications.', '• Design, develop, and maintain efficient, reusable, and reliable code\n• Collaborate with product managers and UX designers to define and implement new features\n• Integrate with front-end and back-end services as needed\n• Write and maintain unit tests to ensure software quality\n• Participate in peer code reviews and documentation efforts\n• Optimise applications for performance, scalability, and security', '• Bachelor\'s degree in Computer Science or related field\n• Minimum 3 years of experience in software development\n• Proficiency in at least one modern programming language (e.g., Java, Python, or C#)\n• Experience with relational databases and writing SQL queries\n• Strong knowledge of software design patterns and object-oriented principles\n• Familiarity with Git and version control workflows', '• Experience working with cloud platforms such as AWS, Azure or GCP\n• Familiarity with Docker or Kubernetes\n• Knowledge of CI/CD tools and processes\n• Exposure to microservices architecture\n• Understanding of Agile or Scrum methodologies\n• Interest or experience in test-driven development (TDD)'),
(2, 'Cybersecurity Analyst', 'G04', 'IT Security Manager', 'AUD $95,000 - $125,000', 'We are looking for a proactive Cybersecurity Analyst to protect our Australian IT infrastructure and digital assets from security threats. You will help implement cybersecurity policies, monitor systems, and respond to incidents to ensure a robust security posture.', '• Monitor and analyse security alerts and logs to detect threats\n• Perform vulnerability assessments and internal penetration tests\n• Develop and enforce information security policies and procedures\n• Respond to security breaches and conduct root cause analysis\n• Manage security tools such as SIEMs, firewalls, and intrusion detection systems\n• Provide training and awareness sessions for staff\n• Stay current with Australian cybersecurity regulations and threat landscapes', '• Bachelor\'s degree in Cybersecurity, IT, or related field\n• Minimum 2 years of hands-on experience in cybersecurity or IT security\n• Working knowledge of frameworks like NIST or ISO 27001\n• Familiarity with tools such as SIEM, IDS/IPS, and endpoint protection\n• Strong grasp of network security, threat analysis, and incident response\n• Analytical mindset and attention to detail', '• Cybersecurity certifications such as CISSP, Security+, or CEH\n• Experience with cloud security (AWS, Azure, or GCP)\n• Knowledge of scripting languages like Python or PowerShell\n• Familiarity with Australian compliance standards (e.g., ASD Essential Eight)\n• Exposure to threat intelligence tools or platforms'),
(3, 'Data Analyst', 'G03', 'Head of Business Intelligence', 'AUD $85,000 - $110,000', 'We are looking for a Data Analyst to join our Australian operations and support data-driven decision-making across departments. You will work closely with business stakeholders to analyse trends, produce actionable insights, and drive continuous improvement.', '• Extract, clean, and analyse large datasets to identify key insights\n• Create dashboards and reports using BI tools (e.g., Power BI, Tableau)\n• Collaborate with stakeholders to define data requirements\n• Deliver accurate and timely reporting to support strategic planning\n• Perform data validation and quality assurance tasks\n• Support forecasting and performance measurement initiatives', '• Bachelor\'s degree in Data Science, Statistics, Computer Science, or related field\n• Minimum 2 years of experience in a data analyst role\n• Strong SQL skills and experience working with relational databases\n• Proficiency in Microsoft Excel and data visualisation tools\n• Excellent attention to detail and critical thinking abilities\n• Strong verbal and written communication skills, including the ability to present complex data to non-technical audiences', '• Experience with Python or R for data analysis\n• Familiarity with Australian data privacy regulations (e.g. APPs under the Privacy Act 1988)\n• Exposure to cloud-based data platforms such as AWS or Google Cloud\n• Knowledge of A/B testing, statistical modelling or machine learning\n• Experience in the finance, healthcare or government sectors'),
(4, 'UX Designer', 'UXD390-AUS', 'Head of Product Design', 'AUD $85,000 - $105,000', 'We are seeking a creative and user-focused UX Designer to join our design team. You will play a key role in crafting intuitive and engaging digital experiences across our web and mobile platforms, helping us put users at the centre of our product development.', '• Conduct user research and usability testing to inform design decisions\n• Create wireframes, prototypes, and user flows\n• Collaborate closely with product managers, developers, and other designers\n• Translate business requirements into user-centric designs\n• Continuously iterate on designs based on feedback and analytics\n• Ensure design consistency and adherence to brand guidelines across all touchpoints', '• Bachelor\'s degree in Design, HCI, or a related field\n• At least 2 years of experience in UX or product design roles\n• Proficiency with design and prototyping tools such as Figma, Sketch, or Adobe XD\n• Strong understanding of user-centred design principles\n• Excellent communication and collaboration skills\n• Experience creating responsive designs for web and mobile', '• Familiarity with HTML/CSS or front-end development\n• Experience with accessibility standards (WCAG 2.1)\n• Background in psychology or behavioural science\n• Prior work in agile teams or product squads\n• Understanding of design systems and component libraries'),
(9, 'Network Administrator', 'G02', 'IT Infrastructure Manager', 'AUD $95,000 - $105,000', 'We are seeking a skilled Network Administrator to manage and optimize our network infrastructure across Australia. You will ensure connectivity, security, and reliability of internal and external communications.', '• Install, configure, and support network hardware and software\n• Monitor network performance and troubleshoot issues\n• Maintain and update documentation for network configurations\n• Implement security measures and ensure compliance\n• Provide support for LAN/WAN, VPN, and VoIP systems', '• Bachelor\'s degree in Information Technology or similar\n• 2+ years of experience in network administration\n• Knowledge of TCP/IP, DNS, DHCP, routing, and switching\n• Familiarity with firewalls, VPNs, and network monitoring tools\n• Strong problem-solving and communication skills', '• CCNA or CompTIA Network+ certification\n• Experience with Cisco, Juniper, or HP networking gear\n• Understanding of network automation tools\n• Knowledge of Australian cyber policies and frameworks'),
(10, 'IT Support Technician', 'G05', 'IT Support Manager', 'AUD $70,000 - $80,000', 'We are hiring an IT Support Technician to provide first and second-line support to our Australian teams. The ideal candidate will have strong communication skills and a solid technical foundation.', '• Provide technical support to staff onsite and remotely\n• Troubleshoot hardware, software, and network issues\n• Set up new user accounts and devices\n• Maintain IT inventory and ensure timely updates\n• Assist with IT onboarding and training', '• Diploma or certificate in IT or related discipline\n• 1-2 years of IT support experience\n• Knowledge of Windows and macOS environments\n• Strong interpersonal and customer service skills\n• Understanding of basic networking concepts', '• CompTIA A+ or similar certification\n• Experience with ticketing systems like Zendesk or Jira\n• Familiarity with Office 365 admin tools\n• Exposure to remote desktop tools (e.g., TeamViewer, AnyDesk)'),
(11, 'Cloud Engineer', 'G06', 'Cloud Services Lead', 'AUD $120,000 - $140,000', 'We are seeking a Cloud Engineer to manage and optimize our cloud infrastructure in Australia. This role involves deploying scalable cloud solutions and ensuring security and cost-efficiency.', '• Design and implement cloud architectures\n• Automate deployment and scaling tasks\n• Monitor system performance and cloud costs\n• Ensure security compliance in cloud environments\n• Collaborate with DevOps and development teams', '• Bachelor\'s degree in Computer Science or similar\n• 3+ years of cloud engineering experience\n• Proficiency with AWS, Azure, or GCP\n• Knowledge of infrastructure-as-code (e.g., Terraform)\n• Experience with CI/CD pipelines', '• Cloud certifications (e.g., AWS Solutions Architect, Azure Admin)\n• Familiarity with Kubernetes and Docker\n• Scripting skills in Python or Bash\n• Understanding of cloud security principles'),
(12, 'AI/ML Engineer', 'G07', 'Head of AI Research', 'AUD $105,000 - $125,000', 'We are looking for an AI/ML Engineer to develop intelligent systems and models as part of our growing AI team in Australia. You will help design, implement, and deploy machine learning solutions for real-world applications.', '• Develop and train machine learning models\n• Work with large datasets and perform feature engineering\n• Deploy models into production environments\n• Evaluate model performance and retrain as needed\n• Collaborate with data scientists, engineers, and product teams', '• Bachelor\'s or Master\'s in AI, Data Science, or similar\n• 2+ years experience in ML development\n• Proficient in Python and ML libraries (e.g., scikit-learn, TensorFlow, PyTorch)\n• Familiarity with cloud-based ML services\n• Strong mathematical and statistical foundations', '• Experience with NLP, computer vision, or reinforcement learning\n• Contributions to open-source projects\n• Published research or participation in Kaggle competitions\n• Understanding of model explainability and fairness');

-- --------------------------------------------------------

--
-- Table structure for table `technical_skills`
--

CREATE TABLE `technical_skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technical_skills`
--

INSERT INTO `technical_skills` (`id`, `name`) VALUES
(1, 'Cloud Computing'),
(2, 'Cybersecurity'),
(3, 'Machine Learning'),
(4, 'Data Analysis'),
(5, 'UI/UX Design'),
(6, 'DevOps'),
(7, 'Agile Methodologies'),
(8, 'Software Testing'),
(9, 'Mobile Development'),
(10, 'Version Control (Git)'),
(11, 'Containerization (Docker)'),
(12, 'Continuous Integration/Delivery'),
(13, 'System Administration'),
(14, 'Technical Writing'),
(15, 'Project Management'),
(16, 'IT Support'),
(17, 'API Development'),
(18, 'Network Configuration'),
(19, 'Scripting (Python/Bash)'),
(20, 'Virtualization (VMware/Hyper-V)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `eoi_skills`
--
ALTER TABLE `eoi_skills`
  ADD KEY `EOInumber` (`EOInumber`);

--
-- Indexes for table `job_listings`
--
ALTER TABLE `job_listings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technical_skills`
--
ALTER TABLE `technical_skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `job_listings`
--
ALTER TABLE `job_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `technical_skills`
--
ALTER TABLE `technical_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eoi_skills`
--
ALTER TABLE `eoi_skills`
  ADD CONSTRAINT `eoi_skills_ibfk_1` FOREIGN KEY (`EOInumber`) REFERENCES `eoi` (`EOInumber`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
