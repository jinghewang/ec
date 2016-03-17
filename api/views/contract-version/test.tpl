{$name}--{$age}
{$name|substr:1:1}
{$data|@count}
{assign var="i" value=0}
{$i}

{date_now}<br>
{date_now format="Y-m-d"}<br>

{date_format format="Y-m" date="2016-01-11 23:58:08"}<br>


{hlt_checkbox value="1"}<br>