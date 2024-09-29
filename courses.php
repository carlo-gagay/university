<?php
require_once './database.php';
?>

<?php
include_once('./header.php');

$done = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $conn = new mysqli($host, $username, $password, $database);
    $id = $_POST['id'];
    $sql = "DELETE FROM courses WHERE course_id=$id";

    if ($conn->query($sql)) {
        $done = 'success';
    } else {
        $done = 'failed';
    }

    $conn->close();
}

require_once './database/courses.php';
?>

<div class="flex items-start w-full">
    <?php include_once('./sidebar.php'); ?>
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
                        href="./course.php"
                        class="px-4 py-2 rounded bg-blue-500 text-white font-bold ml-auto"
                    >
                        Add
                    </a>
                </div>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-start px-2 py-4">Course ID</th>
                            <th class="text-start px-2 py-4">Course Name</th>
                            <th class="text-start px-2 py-4">Credits</th>
                            <th class="text-start px-2 py-4">Department</th>
                            <th class="text-start px-2 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $course['course_id']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $course['course_name']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $course['credits']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <?php echo $course['department']; ?>
                                </td>
                                <td class="px-2 py-4 border-t border-slate-200">
                                    <div class="flex flex-col items-start gap-1">
                                        <a href="./course.php?course_id='<?php echo $course['course_id']; ?>'" class="p-2 rounded bg-orange-200">Edit</a>
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $course['course_id']; ?>" />
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