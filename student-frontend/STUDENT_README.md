# Student Frontend - Student Performance Tracker

## Development Commands

- **Start development server**: `npm run dev`
- **Build for production**: `npm run build`
- **Preview production build**: `npm run preview`
- **Lint and fix code**: `npm run lint`

## Features Overview

### 🔐 **Student Authentication**
- Secure login with email/password
- Token-based authentication
- Auto-logout on token expiration
- Demo credentials: student@example.com / student123

### 📊 **My Dashboard**
- Personal performance overview
- Overall grade average with letter grade
- Attendance rate tracking
- Subject-wise performance bars
- Recent activity feed
- Quick action buttons

### 📈 **My Grades**
- Complete grade history by subject and term
- Subject performance overview with progress bars
- Grade filtering by subject and term
- Visual grade trends and improvement indicators
- Letter grade calculations (A+, A, B, C, D, F)
- Performance statistics

### 📅 **My Attendance**
- Interactive calendar view of attendance
- Monthly navigation
- Color-coded attendance status (Present, Absent, Late)
- Attendance rate statistics
- Recent attendance list with details
- Visual attendance summary

### 💬 **Feedback**
- Teacher feedback and messages
- Categorized feedback (Academic, Behavior, General, Encouragement)
- Search and filter functionality
- Mark messages as read
- Favorite important messages
- Grade-related feedback linking

### 👤 **My Profile**
- Personal information management
- Guardian information
- Academic details (Student ID, Class, etc.)
- Quick stats overview
- Editable profile sections

## Tech Stack

- **Vue 3** with Composition API
- **Vite** for build tooling
- **Tailwind CSS** for styling
- **Pinia** for state management
- **Vue Router** for navigation
- **Axios** for API communication

## Project Structure

```
src/
├── components/
│   ├── StudentLayout.vue      # Main layout wrapper
│   └── StudentNavigation.vue  # Sidebar navigation
├── stores/
│   ├── studentAuth.js         # Authentication state
│   └── studentPerformance.js  # Grades, attendance, feedback
├── services/
│   └── api.js                 # Axios configuration
├── views/
│   ├── StudentLogin.vue       # Login page
│   ├── StudentDashboard.vue   # Main dashboard
│   ├── StudentGrades.vue      # Grades view
│   ├── StudentAttendance.vue  # Attendance calendar
│   ├── StudentFeedback.vue    # Teacher feedback
│   └── StudentProfile.vue     # Profile management
└── router/
    └── index.js               # Route configuration
```

## API Endpoints Expected

The frontend connects to Laravel backend at `http://localhost:8000/api`

### Student Authentication
- `POST /student/login` - Student login
- `POST /student/logout` - Student logout
- `GET /student/profile` - Get student profile
- `PUT /student/profile` - Update student profile

### Student Data
- `GET /student/grades` - Get student's grades
- `GET /student/attendance` - Get attendance records
- `GET /student/feedback` - Get teacher feedback
- `GET /student/performance-overview` - Get performance stats

## State Management

### Pinia Stores

#### studentAuth.js
- User authentication state
- Login/logout functionality
- Profile management
- Token handling

#### studentPerformance.js
- Grades data and statistics
- Attendance records and stats
- Teacher feedback messages
- Performance calculations

## Key Features Detail

### Dashboard
- **Performance Stats**: Overall average, total grades, attendance rate, improvement trend
- **Subject Performance**: Progress bars for each subject with trend indicators
- **Recent Activity**: Latest grades, attendance, and feedback notifications
- **Quick Actions**: Direct navigation to key features

### Grades View
- **Subject Performance Overview**: Visual cards showing average, count, and trend for each subject
- **Grade Details Table**: Complete grade history with filtering options
- **Performance Trends**: Placeholder for future chart integration
- **Grade Calculations**: Automatic letter grade assignment based on percentage

### Attendance Calendar
- **Monthly Calendar**: Interactive grid showing attendance status for each day
- **Color Coding**: Green (Present), Red (Absent), Yellow (Late), Gray (No class)
- **Navigation**: Previous/next month buttons
- **Statistics**: Present/absent/late counts and overall rate
- **Recent List**: Chronological attendance records

### Feedback System
- **Message Types**: Academic, Behavior, General, Encouragement with color coding
- **Search & Filter**: By content, teacher, type, or subject
- **Interactive Features**: Mark as read, favorite messages
- **Grade Integration**: Links to related assignments and grades

### Profile Management
- **Personal Info**: Editable name, email, phone, address, date of birth
- **Academic Info**: Read-only student ID, class, enrollment details
- **Guardian Info**: Editable guardian contact information
- **Quick Stats**: Performance summary cards

## Styling & UI

- **Tailwind CSS**: Utility-first styling
- **Responsive Design**: Mobile-first approach
- **Color Scheme**: Blue/purple gradients with semantic colors
- **Interactive Elements**: Hover states, transitions, loading states
- **Accessibility**: Proper ARIA labels and keyboard navigation

## Security Features

- **Token-based Authentication**: JWT tokens stored in localStorage
- **Route Guards**: Automatic redirect to login for unauthenticated users
- **API Interceptors**: Automatic token inclusion and error handling
- **Auto-logout**: On token expiration or 401 responses

## Demo Account

For testing purposes:
- **Email**: student@example.com
- **Password**: student123

## Development Notes

- All components use Vue 3 Composition API
- State management with Pinia stores
- Responsive design with Tailwind CSS
- Mock data fallbacks for offline development
- Error handling and loading states throughout
- Modular component architecture

## Future Enhancements

- Chart.js integration for grade trends
- Real-time notifications
- Dark mode support
- Mobile app (PWA)
- Offline functionality
- File upload for assignments
