<form action="{{ route('login.store') }}" method="post">
    @csrf
    <input type="text" name="email" value="{{ old('email') }}">
    <input type="password" name="password">

    <button type="submit">Log In</button>
</form>
