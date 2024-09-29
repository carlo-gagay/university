<?php
require_once './database.php';
include_once('./header.php');
$route = 'professors';
?>

<?php
$done = '';
$isUpdate = FALSE;
$id = null;

$conn = new mysqli($host, $username, $password, $database);

$firstName = $lastName = $email = $department = '';

if (isset($_GET['professor_id']) && $_GET['professor_id']) {
    $isUpdate = TRUE;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isUpdate) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $department = $_POST['department'];

    if (
        empty($firstName)
        || empty($lastName)
        || empty($email)
        || empty($department)
    ) {
        $done = 'missing';
    } else {
        if (!$conn) {
            die("Error connection!");
        }

        $sql = "
            INSERT INTO professors (first_name, last_name, department, email)
            VALUES ('{$firstName}', '{$lastName}', '{$department}', '{$email}')
        ";

        if ($conn->query($sql)) {
            $done = 'success';
            $firstName = $lastName = $department = $email = '';
        } else {
            $done = 'failed';
        }
    }
} else {
    if (isset($_GET['professor_id'])) {
        $id = $_GET['professor_id'];
        $sql = "SELECT * FROM professors WHERE professor_id=$id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                $email = $row['email'];
                $department = $row['department'];
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $department = $_POST['department'];

        $sql = "UPDATE professors SET first_name='$firstName', last_name='$lastName', email='$email', department='$department' WHERE professor_id=$id";
        
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
                        Update Professor
                    <?php else: ?>
                        Add New Professor
                    <?php endif; ?>
                </legend>
                <?php if ($done == 'success'): ?>
                    <div class="py-4 px-2 mb-4 bg-green-500 text-white rounded">
                        <?php if($isUpdate): ?>
                            Professor updated! See <a href="./professors.php" class="text-blue-500">list</a>
                        <?php else: ?>
                            New professor added! See <a href="./professors.php" class="text-blue-500">list</a>
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php if ($isUpdate): echo "?professor_id=$id"; endif; ?>" method="POST">
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
                            <label for="department">Department</label>
                            <input
                                type="text"
                                name="department"
                                placeholder="Date of Birth"
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