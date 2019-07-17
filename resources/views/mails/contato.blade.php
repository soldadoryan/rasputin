@extends('mails.internalTemplateMail')
@section('content')
	<p>Cliente está em contato com o Taskinho e ficou com duvida!</p>

	<p><b>Dados do contato: </b><p>
	<p>Nome: {{ $customerName }} <br>
	Email: {{ $customerMail }} <br>
	Telefone:{{ $customerTel }} </p> 	

@endsection