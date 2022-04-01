<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajax - Curd</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>

<body>
    <div class="modal fade" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1" id="modal_frm">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                </div>
                <div class="modal-body">
                    <form action="" id="frm">
                        <input type="hidden" name="action" id="action" value="Insert">
                        <input type="hidden" name="id" id="uid" value="0">
                        <div class="mb-3 form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your Name">
                        </div>
                        <div class="mb-3 form-group">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter your Number">
                        </div>
                        <div class="mb-3 form-group">
                            <input type="submit" value="submit" class="btn btn-success" data-bs-dismiss="modal">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <p class="text-end"><a href="" class="btn btn-success mt-4" id="add_record" data-bs-toggle="modal" data-bs-target="#modal_frm">Add Record</a></p>

        <table class="table table-bordered table-responsive">
            <thead>
                <tr class="text-center">
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbody">

                <?php

                $con = mysqli_connect("localhost", "root", "", "ajax_curd");
                $sql = "SELECT * FROM users";
                $res = $con->query($sql);
                while ($row = $res->fetch_assoc()) {
                    echo "
                    <tr class='text-center' uid={$row['id']}>
                        <td>{$row['name']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['contact']}</td>
                        <td class='d-flex justify-content-around'>
                            <a href='#' class='btn btn-primary w-25 edit'
                            data-bs-toggle='modal' data-bs-target='#modal_frm'>Edit</a>
                            <a href='#' class='btn btn-danger w-25 delete'>Delete</a>
                        </td>
                    </tr>";
                }

                ?>

            </tbody>
        </table>

    </div>



    <script>
        $(document).ready(function() {
            $("#frm").submit(function(e) {
                e.preventDefault();
                var current_row = null;
                $.ajax({
                    url: "ajax_action.php",
                    type: "post",
                    data: $("#frm").serialize(),
                    beforeSend: function() {
                        $("#frm").find("input[type='submit']").val("Loading...");
                    },
                    success: function(res) {
                        if (res) {
                            if ($("#uid").val() == "0") {
                                $("#tbody").append(res);
                            } else {
                                $(current_row).html(res);
                            }
                        } else alert("Failed Try Again!");
                        $("#frm").find("input[type='submit']").val("Submit");
                        clear_input();
                    }
                });
            });

            $("body").on("click", ".edit", function(e) {
                e.preventDefault();
                current_row = $(this).closest("tr");
                var id = $(this).closest("tr").attr("uid");
                var name = $(this).closest("tr").find("td:eq(0)").text();
                var gender = $(this).closest("tr").find("td:eq(1)").text();
                var contact = $(this).closest("tr").find("td:eq(2)").text();

                $("#action").val("Update");
                $("#uid").val(id);
                $("#name").val(name);
                $("#gender").val(gender);
                $("#contact").val(contact);
            });

            $("body").on("click", ".delete", function(e) {
                e.preventDefault();
                var id = $(this).closest("tr").attr("uid");
                var name = $(this).closest("tr").find("td:eq(0)").text();
                if (confirm("Are you sure " + name)) {
                    $.ajax({
                        url: "ajax_action.php",
                        type: "post",
                        data: {
                            uid: id,
                            action: "Delete"
                        },
                        success: function(res) {
                            $(this).closest("tr").remove();
                        }
                    });
                }
            });

            function clear_input() {
                $("#frm").find(".form-control").val("");
                $("#action").val("Insert");
                $("#uid").val("0");
            }

        })
    </script>


</body>

</html>