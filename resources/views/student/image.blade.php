<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WebCam Image Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="p-4">

    <form method="POST" action=" {{ route('student.image', $student->id) }} ">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div id="my_camera" style="width:490px; height:350px;"></div>
                <br />
                <button class="btn btn-primary" type="button" onclick="take_snapshot()">Take Photo</button>
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-6">
                <div id="results">Your captured image will appear here...</div>
            </div>
            <div class="col-md-12 text-center">
                <br />
                <button class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/webcam.js') }}"></script>

    <script>
        $(document).ready(function() {
            Webcam.set({
                width: 350,
                height: 350,
                image_format: 'jpeg',
                jpeg_quality: 90,
                crop_width: 350,
                crop_height: 350,
            });
            Webcam.attach('#my_camera');
        });

        function take_snapshot() {
            Webcam.snap(async function(data_uri) {
                $(".image-tag").val(data_uri);

                const result = await resizeImage(data_uri, 300, 300);

                document.getElementById('results').innerHTML = `<img id="imageData" src="${result}"/>`;


            });
        }

        const resizeImage = (base64Str, maxWidth = 400, maxHeight = 350) => {
            return new Promise((resolve) => {
                let img = new Image()
                img.src = base64Str
                img.onload = () => {
                    let canvas = document.createElement('canvas')
                    const MAX_WIDTH = maxWidth
                    const MAX_HEIGHT = maxHeight
                    let width = img.width
                    let height = img.height

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width
                            width = MAX_WIDTH
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height
                            height = MAX_HEIGHT
                        }
                    }
                    canvas.width = width
                    canvas.height = height
                    let ctx = canvas.getContext('2d')
                    ctx.drawImage(img, 0, 0, width, height)
                    resolve(canvas.toDataURL())
                }
            })
        }
    </script>


</body>

</html>
