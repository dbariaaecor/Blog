<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>



        <input type="file"  class="file" multiple>
        <div class="row">
            <div class="pimg img-thumbnail" style="max-width: 200px; max-height:200px;"></div>
        </div>
         <div><button class="btn btn-primary" id="update">update</button></div>


    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            var name = "";
            var arrayimg = [];
            $('.file').each(function(){
               $(this).on('change',function(){
                   // console.log(this.files[0].name)
                   var filesArr = Array.prototype.slice.call(this.files);
                    filesArr.forEach(function(f,i){
                        arrayimg.push(f);
                        var reader = new FileReader();
                        reader.onload = function(event){
                        $('.pimg').append('<div class="pimgcon" style="margin:10px; "><button class="close" style="position: relative; left:100%;">X</button><img src="'+event.target.result+'" style="width:100%; height:100%;" data-name="'+ f.name  +'" /></div>')
                         }
                        reader.readAsDataURL(f);
                        // console.log(arrayimg[i]);
                    });
                });
               });
            $(document).on('click','.close',function(){
                // console.clear();
                //find image
                console.clear();
                var name = $(this).siblings('img').data('name');
                console.log(name);
               for (let index = 0; index<arrayimg.length; index++) {

                    if(arrayimg[index].name===name){
                        arrayimg.splice(index,1);
                    }
               }

               for (let index = 0; index < arrayimg.length; index++) {
                   const element = arrayimg[index];
                   console.log(element);
               }
               $(this).parents('.pimgcon').remove();
            });
            $('#update').click(function(){

            $.ajaxSetup({
               headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            }
            );
            var file = arrayimg;
            console.log();
            $.ajax({
                type:'post',
                url:'{{route("update")}}',
                data: JSON.stringify({ paramName: file }),
                success:function(data){
                    console.log(data);
                },
                error:function(data){
                    console.log("error");
                }
            });
        });
        });



    </script>
</body>
</html>
