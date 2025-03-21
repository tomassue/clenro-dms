<div>
    <style>
        /* LOADING SPINNER */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10000;
            width: 100%;
            height: 100%;
            /* display: none; */
            /* Initially hidden */
            background: rgba(0, 0, 0, 0.5);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #639a56 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- This spinner will display whenever there's a request to the server except what was indicated in wire:target -->
    <div id="overlay" wire:loading>
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
</div>