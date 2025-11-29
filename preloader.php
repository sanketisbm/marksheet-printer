<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .preloader {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100vh;
            z-index: 9999;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .preloader-only {
            width: 40px;
            height: 30px;
            background:
                var(--c) 0 100%/8px 30px,
                var(--c) 50% 100%/8px 20px,
                var(--c) 100% 100%/8px 10px;
            position: relative;
            clip-path: inset(-100% 0);
        }

        .preloader-only:before {
            content: "";
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4B49AC;
            left: -16px;
            top: 0;
            animation:
                l5-1 2s linear infinite,
                l5-2 0.5s cubic-bezier(0, 200, .8, 200) infinite;
        }

        @keyframes l5-1 {
            0% {
                left: -16px;
                transform: translateY(-8px)
            }

            100% {
                left: calc(100% + 8px);
                transform: translateY(22px)
            }
        }

        @keyframes l5-2 {
            100% {
                top: -0.1px
            }
        }



        /* .preloader .spinner-border {
            width: 3rem;
            height: 3rem;
        } */
    </style>
</head>

<body>
    <!-- <div class="preloader"></div> -->
    <div class="preloader">
        <div class="preloader-only text-primary" role="status">
            <!-- <span class="sr-only"></span> -->
        </div>
    </div>
</body>

</html>