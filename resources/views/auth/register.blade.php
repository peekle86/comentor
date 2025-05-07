<form action="{{ route('register.store') }}" method="post">
    @csrf
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name') }}">
    @error('name')
    <span>{{ $message }}</span>
    @enderror

    <label>Email</label>
    <input type="text" name="email" value="{{ old('email') }}">
    @error('email')
    <span>{{ $message }}</span>
    @enderror

    <label>Password</label>
    <input type="password" name="password">
    @error('password')
    <span>{{ $message }}</span>
    @enderror

    <label>Confirm Password</label>
    <input type="password" name="password_confirmation">

    <button type="submit">Register</button>
</form>
