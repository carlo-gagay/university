<?php
require_once './database.php';
include_once('./header.php');
$route = 'courses';
?>

<?php
$done = '';
$isUpdate = FALSE;
$id = null;

$conn = new mysqli($host, $username, $password, $database);

$courseName = $credits = $department = '';

if (isset($_GET['course_id']) && $_GET['course_id']) {
    $isUpdate = TRUE;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isUpdate) {
    $courseName = $_POST['course_name'];
    $credits = $_POST['credits'];
    $department = $_POST['department'];

    if (
        empty($courseName)
        || empty($credits)
        || empty($department)
    ) {
        $done = 'missing';
    } else {
        if (!$conn) {
            die("Error connection!");
        }

        $sql = "
            INSERT INTO courses (course_name, credits, department)
            VALUES ('{$courseName}', '{$credits}', '{$department}')
        ";

        if ($conn->query($sql)) {
            $done = 'success';
            $courseName = $credits = $department = '';
        } else {
            $done = 'failed';
        }
    }
} else {
    if (isset($_GET['course_id'])) {
        $id = $_GET['course_id'];
        $sql = "SELECT * FROM courses WHERE course_id=$id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courseName = $row['course_name'];
                $credits = $row['credits'];
                $department = $row['department'];
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $courseName = $_POST['course_name'];
        $credits = $_POST['credits'];
        $department = $_POST['department'];

        $sql = "UPDATE courses SET course_name='$courseName', credits='$credits', department='$department' WHERE course_id=$id";
        
        if ($conn->query($sql)) {
            $done = 'success';
        } else {
            $done = 'failed';
        }
    }
}

$conn->close();
?>

<div class="flex items-start w-full">
    <?php include_once('./sidebar.php'); ?>
    <div class="w-[680px]">
        <div class="p-8">
            <div class="rounded-lg p-8 border border-slate-200">
                <legend class="text-xl font-bold mb-4">
                    <?php if ($isUpdate): ?>
                        Update Course
                    <?php else: ?>
                        Add New Course
                    <?php endif; ?>
                </legend>
                <?php if ($done == 'success'): ?>
                    <div class="py-4 px-2 mb-4 bg-green-500 text-white rounded">
                        <?php if($isUpdate): ?>
                            Course updated! See <a href="./courses.php" class="text-blue-500">list</a>
                        <?php else: ?>
                            New student added! See <a href="./courses.php" class="text-blue-500">list</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($done == 'failed'): ?>
                    <div class="py-4 px-2 mb-4 bg-red-500 text-white rounded">
                        Failed!
                    </div>
                <?php endif; ?>
                <?php if ($done == 'missing'): ?>
                    <div class="py-4 px-2 mb-4 bg-red-500 text-white rounded">
                        Required field is missing!
                    </div>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php if ($isUpdate): echo "?course_id=$id"; endif; ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="flex flex-col gap-y-4">
                        <div class="block flex flex-col gap-y-2">
                            <label for="course_name">Course Name</label>
                            <input
                                type="text"
                                name="course_name"
                                placeholder="Course Name"
                                id="course_name"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $courseName ?>"
                            />
                        </div>
                        <div class="block flex flex-col gap-y-2">
                            <label for="credits">Credits</label>
                            <input
                                type="number"
                                name="credits"
                                placeholder="Credits"
                                id="credits"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $credits ?>"
                            />
                        </div>
                        <div class="block flex flex-col gap-y-2">
                            <label for="department">Department</label>
                            <input
                                type="text"
                                name="department"
                                placeholder="Department"
                                id="department"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $department ?>"
                            />
                        </div>
                        <button type="submit" class="bg-blue-500 rounded w-fit ml-auto px-4 py-2 text-white mt-4">
                            <?php if($isUpdate): ?>
                                Update
                            <?php else: ?>
                                Submit
                            <?php endif ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once('./footer.php');
?>