<aside class="border-r border-slate-200 w-[480px] h-[calc(100vh-88px)] py-8">
    <div class="flex flex-col">
        <a
            href="./index.php"
            class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg <?php if($route == 'students'): echo ' bg-blue-500 text-white'; else: echo ' hover:bg-blue-100'; endif; ?>"
        >
            Students
        </a>
        <a
            href="./professors.php"
            class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg <?php if($route == 'professors'): echo ' bg-blue-500 text-white'; else: echo ' hover:bg-blue-100'; endif; ?>"
        >
            Professors
        </a>
        <a
            href="./courses.php"
            class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg <?php if($route == 'courses'): echo ' bg-blue-500 text-white'; else: echo ' hover:bg-blue-100'; endif; ?>"
        >
            Courses
        </a>
        <a
            href="./enrollments.php"
            class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg <?php if($route == 'enrollments'): echo ' bg-blue-500 text-white'; else: echo ' hover:bg-blue-100'; endif; ?>"
        >
            Enrollments
        </a>
        <a
            href="./assignments.php"
            class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg <?php if($route == 'assignments'): echo ' bg-blue-500 text-white'; else: echo ' hover:bg-blue-100'; endif; ?>"
        >
            Assignments
        </a>
    </div>
</aside>