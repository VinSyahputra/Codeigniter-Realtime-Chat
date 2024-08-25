<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Counter</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
</head>

<body>
    <div class="container mx-auto">
        <div class="card bg-light text-primary-content w-100 shadow-xl">
            <div class="card-header mx-auto">
                <h3 class="card-title
                    text-primary">Register</h3>
            </div>
            <div class="card-body">
                <form action="/register" method="post">
                    <?= csrf_field() ?>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text
                            text-primary">Username</span>
                        </label>
                        <input type="text" name="username" placeholder="Username" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text
                            text-primary">Email</span>
                        </label>
                        <input type="email" name="email" placeholder="Email" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text
                            text-primary">Password</span>
                        </label>
                        <input type="password" name="password" placeholder="Password" class="input input-bordered" id="password" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text
                            text-primary">Confirm Password</span>
                        </label>
                        <input type="password" placeholder="Confirm Password" class="input input-bordered" id="confirm-password" />
                    </div>
                    <div class="form-control">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm-password');
        const form = document.querySelector('form');

        form.addEventListener('submit', (e) => {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Password and Confirm Password must be the same');
            }
        });
    </script>
</body>

</html>