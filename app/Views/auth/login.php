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
                    text-primary">Login</h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="">
                        <div role="alert" class="alert alert-success">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 shrink-0 stroke-current"
                                fill="none"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?= session()->getFlashdata('success'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <form action="/login" method="post">
                    <div class="form-control">
                        <label class="label"><span class="label-text text-primary">Username</span> </label>
                        <input type="text" name="username" placeholder="Username" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text text-primary">Password</span> </label>
                        <input type="password" name="password" placeholder="Password" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <a href="/register" class="btn btn-outline-primary mb-2">Register</a>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>



</body>

</html>