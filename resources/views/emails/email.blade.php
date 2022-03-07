<!DOCTYPE html>
<html>
<head>
 <title>Feedback Form</title>
</head>
<body>
    <h3>Sender Name: {{ $data['name'] }}</h3>
    <h3>Sender Emil: {{ $data['email'] }}</h3>
    <h3>Sender Phone Number: {{ $data['cellno'] }}</h3>

    <h6>Message:
    <p>{{$data['msg']}}</p>
    </h6>

</body>
</html> 