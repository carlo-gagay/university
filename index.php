<?php
require_once './database.php';
?>

<?php
include_once('./header.php');

$done = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $conn = new mysqli($host, $username, $password, $database);
    $id = $_POST['id'];
    $sql = "DELETE FROM students WHERE student_id=$id";

    if ($conn->query($sql)) {
        $done = 'success';
    } else {
        $done = 'failed';
    }

    $conn->close();
}

require_once './database/students.php';
?>

<div class="flex items-start w-full">
    <aside class="border-r border-slate-200 w-[480px] h-[calc(100vh-88px)] py-8">
        <div class="flex flex-col">
            <a href="./index.php" class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg hover:bg-blue-100 bg-blue-200">
                Students
            </a>
            <a href="#" class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg hover:bg-blue-100">
                Professors
            </a>
            <a href="#" class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg hover:bg-blue-100">
                Courses
            </a>
            <a href="#" class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg hover:bg-blue-100">
                Enrollments
            </a>
            <a href="#" class="w-full px-4 py-2 text-blue-500 font-bold transition text-lg hover:bg-blue-100">
                Assignments
            </a>
        </div>
    </aside>
    <div class="w-full">
        <div class="p-8">
            <div class="rounded-lg p-8 border border-slate-200">
                <?php if($done == 'success'): ?>
                    <div class="py-4 px-2 mb-4 bg-green-500 text-white rounded">
                        Delete success!
                    </div>
                <?php elseif ($done == 'failed'): ?>
                    <div class="py-4 px-2 mb-4 bg-red-500 text-white rounded">
                        Delete failed!
                    </div>
                <?php endif; ?>
                <div class="w-full">
                    <a
                        href="./student.php"
                        class="px-4 py-2 rounded bg-blue-500 text-white font-bold ml-auto"
                    >
                        Add
                    </a>
                </div>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-start px-2 py-4">Student ID</th>
                            <th class="text-start px-2 py-4">First Name</th>
                            <th class="text-start px-2 py-4">Last Name</th>
                            <th class="text-start px-2 py-4">Date of Birth</th>
                            <th class="text-start px-2 py-4">Email</th>
                            <th class="text-start px-2 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $student['student_id']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $student['first_name']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $student['last_name']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $student['dob']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $student['email']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <div class="flex flex-col items-start gap-1">
                                        <a href="./student.php?student_id='<?php echo $student['student_id']; ?>'" class="p-2 rounded bg-orange-200">Edit</a>
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $student['student_id']; ?>" />
                                            <button type="submit" class="p-2 rounded bg-red-300">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include_once('./footer.php');
?>