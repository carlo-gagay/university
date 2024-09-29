<?php
require_once './database.php';
require_once './database/students.php';
require_once './database/courses.php';
include_once('./header.php');
$route = 'enrollments';
?>

<?php
$done = '';
$isUpdate = FALSE;
$id = null;

$conn = new mysqli($host, $username, $password, $database);

$student_id = $course_id = $enrollment_date = '';

if (isset($_GET['enrollment_id']) && $_GET['enrollment_id']) {
    $isUpdate = TRUE;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isUpdate) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $enrollment_date = $_POST['enrollment_date'];

    if (
        empty($student_id)
        || empty($course_id)
        || empty($enrollment_date)
    ) {
        $done = 'missing';
    } else {
        if (!$conn) {
            die("Error connection!");
        }

        $sql = "
            INSERT INTO enrollments (student_id, course_id, enrollment_date)
            VALUES ('{$student_id}', '{$course_id}', '{$enrollment_date}')
        ";

        if ($conn->query($sql)) {
            $done = 'success';
            $student_id = $course_id = $enrollment_date = '';
        } else {
            $done = 'failed';
        }
    }
} else {
    if (isset($_GET['enrollment_id'])) {
        $id = $_GET['enrollment_id'];
        $sql = "SELECT * FROM enrollments WHERE enrollment_id=$id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $student_id = $row['student_id'];
                $course_id = $row['course_id'];
                $enrollment_date = $row['enrollment_date'];
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['enrollment_id'];
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];
        $enrollment_date = $_POST['enrollment_date'];

        $sql = "UPDATE enrollments SET student_id='$student_id', course_id='$course_id', enrollment_date='$enrollment_date' WHERE enrollment_id=$id";
        
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
                        Update Enrollment
                    <?php else: ?>
                        Add New Enrollment
                    <?php endif; ?>
                </legend>
                <?php if ($done == 'success'): ?>
                    <div class="py-4 px-2 mb-4 bg-green-500 text-white rounded">
                        <?php if($isUpdate): ?>
                            Enrollment updated! See <a href="./enrollments.php" class="text-blue-500">list</a>
                        <?php else: ?>
                            New student added! See <a href="./enrollments.php" class="text-blue-500">list</a>
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php if ($isUpdate): echo "?enrollment_id=$id"; endif; ?>" method="POST">
                    <input type="hidden" name="enrollment_id" value="<?php echo $id; ?>">
                    <div class="flex flex-col gap-y-4">
                        <div class="block flex flex-col gap-y-2">
                            <label for="student_id">Select Student</label>
                            <select
                                name="student_id"
                                placeholder="Select Student"
                                id="student_id"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $student_id ?>"
                            >
                                <?php foreach($students as $student): ?>
                                    <option
                                        value="<?php echo $student['student_id']; ?>"
                                        <?php if($student_id == $student['student_id']): echo 'selected'; endif; ?>
                                    >
                                        <?php echo $student['last_name'] . ', ' . $student['first_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="block flex flex-col gap-y-2">
                            <label for="course_id">Select Course</label>
                            <select
                                name="course_id"
                                placeholder="Select Course"
                                id="course_id"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $course_id ?>"
                            >
                                <?php foreach($courses as $course): ?>
                                    <option
                                        value="<?php echo $course['course_id']; ?>"
                                        <?php if($course_id == $course['course_id']): echo 'selected'; endif; ?>
                                    >
                                        <?php echo $course['course_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="block flex flex-col gap-y-2">
                            <label for="enrollment_date">Date of Enrollment</label>
                            <input
                                type="date"
                                name="enrollment_date"
                                placeholder="Date of Enrollment"
                                id="enrollment_date"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $enrollment_date ?>"
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