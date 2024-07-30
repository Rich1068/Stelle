@extends('layouts.app')

@section('body')
<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Create Your Certificate</h1>
    <div id="container" style="border: 1px solid #ddd; width: 800px; height: 600px;"></div>
    <br/>
    <button onclick="addText()" class="btn btn-primary mt-3">Add Text</button>
    <button onclick="addImage()" class="btn btn-secondary mt-3">Add Image</button>
    <button onclick="exportImage()" class="btn btn-success mt-3">Save as Image</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/konva@9.2.2/konva.min.js"></script>
<script>
    const stage = new Konva.Stage({
        container: 'container',
        width: 800,
        height: 600,
    });

    const layer = new Konva.Layer();
    stage.add(layer);

    function addText() {
        const text = new Konva.Text({
            x: 100,
            y: 100,
            text: 'Your Text Here',
            fontSize: 20,
            fontFamily: 'Arial',
            fill: '#333',
            draggable: true
        });
        layer.add(text);
        layer.draw();
    }

    function addImage() {
        const imageObj = new Image();
        imageObj.onload = function () {
            const konvaImage = new Konva.Image({
                x: 200,
                y: 200,
                image: imageObj,
                width: 150,
                height: 150,
                draggable: true
            });
            layer.add(konvaImage);
            layer.draw();
        };
        imageObj.src = 'https://via.placeholder.com/150'; // Replace with your image source
    }

    function exportImage() {
        const dataURL = stage.toDataURL();
        const link = document.createElement('a');
        link.href = dataURL;
        link.download = 'certificate.png';
        link.click();
    }
</script>
@endsection
