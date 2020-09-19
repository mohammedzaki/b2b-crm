<!DOCTYPE html>
<html>
<head>

</head>
<body>

<htmlpageheader name="pageHeader">
    <img src="var:letrImg">
</htmlpageheader>
<sethtmlpageheader name="pageHeader" value="{{ $showLetterHead }}" show-this-page="1"></sethtmlpageheader>

{!! $reportHTML !!}

</body>
</html>