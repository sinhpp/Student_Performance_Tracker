# Admin Frontend - Student Performance Tracker

## Development Commands

- **Start development server**: `npm run dev`
- **Build for production**: `npm run build`
- **Preview production build**: `npm run preview`
- **Lint and fix code**: `npm run lint`
- **Format code**: `npm run format`

## Architecture

### Tech Stack
- Vue 3 with Composition API
- Vite for build tooling
- Tailwind CSS for styling
- Pinia for state management
- Vue Router for navigation
- Axios for API communication

### Project Structure
```
src/
├── components/          # Reusable components
│   ├── Layout.vue      # Main layout wrapper
│   └── Navigation.vue  # Sidebar navigation
├── stores/             # Pinia stores
│   ├── auth.js        # Authentication state
│   ├── student.js     # Student management
│   ├── grade.js       # Grade management
│   └── attendance.js  # Attendance tracking
├── services/          # API services
│   └── api.js         # Axios configuration
├── views/             # Page components
│   ├── Login.vue      # Login page
│   ├── Dashboard.vue  # Main dashboard
│   ├── Students/      # Student management pages
│   ├── Grades/        # Grade management pages
│   └── Attendance/    # Attendance tracking pages
└── router/            # Vue Router configuration
    └── index.js
```

## Features Implemented

### 🔐 Authentication
- Token-based authentication
- Role-based route guarding (admin access)
- Auto-logout on token expiration
- Demo credentials: admin@example.com / admin123

### 👥 Student Management
- List all students with search and filtering
- Add new students with full profile information
- Edit existing student details
- Delete students with confirmation
- Guardian information tracking

### 📊 Grade Management
- View all grades with filtering by subject/term/class
- Add/edit grades with automatic letter grade calculation
- Grade statistics (average, highest, lowest scores)
- Subject and term management

### 📅 Attendance Management
- Mark daily attendance (Present/Absent/Late)
- Bulk attendance operations
- Real-time attendance statistics
- Class-based filtering
- Attendance history tracking

### 📈 Dashboard
- Real-time statistics overview
- Quick action buttons for common tasks
- Recent activity feed
- Performance metrics

## API Integration

The application connects to a Laravel backend API at `http://localhost:8000/api`

### Authentication Flow
1. Login with email/password
2. Receive JWT token and user data
3. Store token in localStorage
4. Include token in all API requests
5. Auto-logout on 401 responses

### API Endpoints Expected
- `POST /login` - User authentication
- `POST /logout` - User logout
- `GET /user` - Get current user
- `GET /students` - List students
- `POST /students` - Create student
- `PUT /students/{id}` - Update student
- `DELETE /students/{id}` - Delete student
- `GET /grades` - List grades
- `POST /grades` - Create grade
- `PUT /grades/{id}` - Update grade
- `DELETE /grades/{id}` - Delete grade
- `GET /attendances` - List attendance records
- `POST /attendances` - Mark attendance
- `POST /attendances/bulk` - Bulk attendance marking

## State Management

### Pinia Stores
- **authStore**: User authentication, login/logout
- **studentStore**: Student CRUD operations and filtering
- **gradeStore**: Grade management and statistics
- **attendanceStore**: Attendance tracking and stats

## Routing

Protected routes require authentication and admin role:
- `/` → Redirects to dashboard
- `/login` → Login page (public)
- `/dashboard` → Main dashboard
- `/students` → Student list
- `/students/create` → Add student
- `/students/:id/edit` → Edit student
- `/grades` → Grade management
- `/attendance` → Attendance tracking

## Styling

- Tailwind CSS for utility-first styling
- Responsive design (mobile-first)
- Consistent color scheme and spacing
- Custom component styles in scoped CSS

## Error Handling

- API error interceptors
- Form validation with error display
- Network error handling
- User-friendly error messages

## Demo Login

For testing purposes, use these credentials:
- **Email**: admin@example.com
- **Password**: admin123

Note: The application expects a Laravel backend API to be running on `http://localhost:8000`
