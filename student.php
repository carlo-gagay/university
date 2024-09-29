<?php
require_once './database.php';
include_once('./header.php');
?>

<?php
$done = '';
$isUpdate = FALSE;
$id = null;

$conn = new mysqli($host, $username, $password, $database);

$firstName = $lastName = $email = $dob = '';

if (isset($_GET['student_id']) && $_GET['student_id']) {
    $isUpdate = TRUE;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isUpdate) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    if (
        empty($firstName)
        || empty($lastName)
        || empty($email)
        || empty($dob)
    ) {
        $done = 'missing';
    } else {
        if (!$conn) {
            die("Error connection!");
        }

        $sql = "
            INSERT INTO students (first_name, last_name, dob, email)
            VALUES ('{$firstName}', '{$lastName}', '{$dob}', '{$email}')
        ";

        if ($conn->query($sql)) {
            $done = 'success';
            $firstName = $lastName = $dob = $email = '';
        } else {
            $done = 'failed';
        }
    }
} else {
    if (isset($_GET['student_id'])) {
        $id = $_GET['student_id'];
        $sql = "SELECT * FROM students WHERE student_id=$id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                $email = $row['email'];
                $dob = $row['dob'];
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];

        $sql = "UPDATE students SET first_name='$firstName', last_name='$lastName', email='$email', dob='$dob' WHERE student_id=$id";
        
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
    <div class="w-[680px]">
        <div class="p-8">
            <div class="rounded-lg p-8 border border-slate-200">
                <legend class="text-xl font-bold mb-4">
                    <?php if ($isUpdate): ?>
                        Update Student
                    <?php else: ?>
                        Add New Student
                    <?php endif; ?>
                </legend>
                <?php if ($done == 'success'): ?>
                    <div class="py-4 px-2 mb-4 bg-green-500 text-white rounded">
                        <?php if($isUpdate): ?>
                            Student updated! See <a href="./index.php" class="text-blue-500">list</a>
                        <?php else: ?>
                            New student added! See <a href="./index.php" class="text-blue-500">list</a>
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php if ($isUpdate): echo "?student_id=$id"; endif; ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="flex flex-col gap-y-4">
                        <div class="block flex flex-col gap-y-2">
                            <label for="first_name">First Name</label>
                            <input
                                type="text"
                                name="first_name"
                                placeholder="First Name"
                                id="first_name"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $firstName ?>"
                            />
                        </div>
                        <div class="block flex flex-col gap-y-2">
                            <label for="last_name">Last Name</label>
                            <input
                                type="text"
                                name="last_name"
                                placeholder="Last Name"
                                id="last_name"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $lastName ?>"
                            />
                        </div>
                        <div class="block flex flex-col gap-y-2">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                name="email"
                                placeholder="Email"
                                id="email"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $email ?>"
                            />
                        </div>
                        <div class="block flex flex-col gap-y-2">
                            <label for="dob">Date of Birth</label>
                            <input
                                type="date"
                                name="dob"
                                placeholder="Date of Birth"
                                id="dob"
                                required
                                class="border border-slate-500 focus:outline-blue-500 px-4 py-2 rounded"
                                value="<?php echo $dob ?>"
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