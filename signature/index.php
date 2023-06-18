developed by Atanu-exe 
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if($_SERVER['REQUEST_METHOD']=='POST'){

     // Usage example
     $base64Image = $_POST['image'];;
     $outputFilePath = date('y-m-d-H-i-s-A')."image.png";

     
    base64ToPNG($base64Image, $outputFilePath) ;

    // method 2 
    base64ToPNG2($base64Image, $outputFilePath) ;
     
     
    

    }
 
    function base64ToPNG($base64Image, $outputFilePath) {
        // Remove data URI scheme and save the image to a file
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
     file_put_contents($outputFilePath,$imageData);

        // image auto download 
     header('Content-Type: application/octet-stream');
     header('Content-Transfer-Encoding: Binary');
     header('Content-Disposition: attachment; filename="' . basename($outputFilePath) . '"');
     header('Content-Length: ' . filesize($outputFilePath));
     readfile($outputFilePath);
     unlink($outputFilePath);
        
    }
    function base64ToPNG2($base64Image, $outputFilePath) {
        $image_parts = explode(";base64,", $base64Image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.' . $image_type;
        $file = $outputFilePath.'method2'. $fileName;

        file_put_contents($file, $image_base64);

        // image auto download 
        // header('Content-Type: application/octet-stream');
        // header('Content-Transfer-Encoding: Binary');
        // header('Content-Disposition: attachment; filename="' . basename($outputFilePath) . '"');
        // header('Content-Length: ' . filesize($outputFilePath));
        // readfile($outputFilePath);
    }
    
 
   

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinature</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        canvas {
            border: 1px solid green !important;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            Featured
        </div>
        <div class="card-body">
            <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">click to
                sign</button>
        </div>
    </div>
    <form action="" method="post" id="sign-form">
        <textarea name="image" id="signature_capture" cols="30" rows="10"></textarea>
    </form>
    <!-- modal  -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class=" modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Signature </h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
                <div class="modal-body">
                    <div id="signatureparent">
                        <div id="signature"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-auto"><button class="btn btn-danger clear-button">Clear</button></div>
                        <div class="col-md-auto"><button class="btn btn-primary disabled submit-button" onclick='submit_form($("#sign-form").submit())'>Accept</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="src/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="src/jSignature.js"></script>
    <!-- optional plugins -->
    <script src="src/plugins/jSignature.CompressorBase30.js"></script>
    <script src="src/plugins/jSignature.CompressorSVG.js"></script>
    <script src="src/plugins/jSignature.UndoButton.js"></script>
    <script src="src/plugins/signhere/jSignature.SignHere.js"></script>
    <script>
       
        $("#signatureparent").jSignature({
            color: "#f00",
            lineWidth: 2,
            width: 400,
            height: 200
        });
        $('.clear-button').on('click', function (e) {
            e.preventDefault();
            $('#signatureparent').jSignature("reset");
        });
        $('.submit-button').on('click', function (e) {
            e.preventDefault();
            if (isValidSignature()) {
                $('.submit-button').removeClass('disabled');
            } else {
                $('.submit-button').addClass('disabled');
            }
        });
        $("#signatureparent").bind("change", function (event) {
            if (isValidSignature()) {
                $('.submit-button').removeClass('disabled');
            } else {
                $('.submit-button').addClass('disabled');
            }
        });
        $('#signatureparent').bind('change', function (e) {
            var data = $('#signatureparent').jSignature('getData');
            $("#signature_capture").val(data);
          
        });
        function submit_form(){
            
        }
        function isValidSignature() {
            var canvas = $('#signatureparent canvas')[0];
            var ctx = canvas.getContext('2d');
            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var filledCount = 0;
            var totalCount = 0;
            for (var i = 0; i < imageData.data.length; i++) {
                if (imageData.data[i] > 0) {
                    filledCount++;
                }
                totalCount++;
            }
            var percentRequired = 0;
            if (window.innerWidth < 330) {
                percentRequired = 3;
            } else if (window.innerWidth > 330 && window.innerWidth < 400) {
                percentRequired = 2;
            } else {
                percentRequired = 0.95;
            }
            console.log(`total filled: ${filledCount / totalCount * 100} / ${percentRequired}`);
            return ((filledCount / totalCount) * 100) > percentRequired;
        }


    </script>
</body>

</html>