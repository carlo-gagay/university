<?php
require_once './database.php';
require_once './database/professors.php';
require_once './database/courses.php';
include_once('./header.php');
$route = 'assignments';
?>

<?php
$done = '';
$isUpdate = FALSE;
$id = null;

$conn = new mysqli($host, $username, $password, $database);

$professor_id = $course_id = '';

if (isset($_GET['assignment_id']) && $_GET['assignment_id']) {
    $isUpdate = TRUE;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isUpdate) {
    $professor_id = $_POST['professor_id'];
    $course_id = $_POST['course_id'];

    if (
        empty($professor_id)
        || empty($course_id)
    ) {
        $done = 'missing';
    } else {
        if (!$conn) {
            die("Error connection!");
        }

        $sql = "
            INSERT INTO assignments (professor_id, course_id)
            VALUES ('{$professor_id}', '{$course_id}')
        ";

        if ($conn->query($sql)) {
            $done = 'success';
            $professor_id = $course_id = '';
        } else {
            $done = 'failed';
        }
    }
} else {
    if (isset($_GET['assignment_id'])) {
        $id = $_GET['assignment_id'];
        $sql = "SELECT * FROM assignments WHERE assignment_id=$id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $professor_id = $row['professor_id'];
                $course_id = $row['course_id'];
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['assignment_id'];
        $professor_id = $_POST['professor_id'];
        $course_id = $_POST['course_id'];

        $sql = "UPDATE assignments SET professor_id='$professor_id', course_id='$course_id' WHERE assignment_id=$id";
        
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
                        Update Assignment
                    <?php else: ?>
                        Add New Assignment
                    <?php endif; ?>
                </legend>
                <?php if ($done == 'success'): ?>
                    <div class="py-4 px-2 mb-4 bg-green-500 text-white rounded">
                        <?php if($isUpdate): ?>
                            Assignment updated! See <a href="./assignments.php" class="text-blue-500">list</a>
                        <?php else: ?>
                            New professor added! See <a href="./assignments.php" class="text-blue-500">list</a>
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php if ($isUpdate): echo "?assignment_id=$id"; endif; ?>" method="POST">
                    <input type="hidden" name="assignment_id" value="<?php echo $id; ?>">
                    <div class="flex flex-col gap-y-4">
                        <div class="block flex flex-col gap-y-2">
                            <label for="professor_id">Select Professor</label>
                            <select
                                name="professor_id"
                                placeholder="Select Professor"
                                id="professor_id"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $professor_id ?>"
                            >
                                <?php foreach($professors as $professor): ?>
                                    <option
                                        value="<?php echo $professor['professor_id']; ?>"
                                        <?php if($professor_id == $professor['professor_id']): echo 'selected'; endif; ?>
                                    >
                                        <?php echo $professor['last_name'] . ', ' . $professor['first_name'] ?>
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