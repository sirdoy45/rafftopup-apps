@extends('layouts.auth')

@section('content')

<div class="page-content page-auth" id="register">
    <div class="section-store-auth" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center justify-content-center row-login">
                <div class="col-lg-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/RAFFSTORE-regis.png') }}" alt="Raff Store" class="img-fluid" style="width: 220px;">
                    </div>
                    <form class="mt-3" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label>Full Name</label>
                            <input 
                                id="name" 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autocomplete="name" 
                                autofocus
                                placeholder="Enter Your Name"
                            >
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input 
                                @change="checkForEmailAvailability()"
                                id="email" 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                :class="{ 'is-invalid': this.email_unavailable }"
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email"
                                placeholder="game@game.co.id"
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input 
                                id="password" 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input 
                                id="password-confirmation" 
                                type="password" 
                                class="form-control @error('password_confirmation') is-invalid @enderror" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                            >
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-block mt-4"
                            :disabled="this.email_unavailable"
                        >
                            Register
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-signup btn-block mt-2">
                            Back To Sign
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script src="/vendor/vue/vue.js"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    Vue.use(Toasted);

    var register = new Vue({
        el: "#register",
        mounted() {
            AOS.init();
        },
        methods: {
            checkForEmailAvailability: function () {
                var self = this;
                axios.get('{{ route('api-register-check') }}', {
                    params: {
                        email: this.$el.querySelector('#email').value
                    }
                })
                .then(function (response) {
                    if(response.data == 'Available') {
                        self.$toasted.show(
                            "Email anda tersedia! Silahkan lanjut langkah selanjutnya!", {
                                position: "top-center",
                                className: "rounded",
                                duration: 5000,
                            }
                        );
                        self.email_unavailable = false;
                    } else {
                        self.$toasted.error(
                            "Maaf, tampaknya email sudah terdaftar pada sistem kami.", {
                                position: "top-center",
                                className: "rounded",
                                duration: 5000,
                            }
                        );
                        self.email_unavailable = true;
                    }
                });
            }
        },
        data() {
            return {
                email_unavailable: false
            }
        },
    });
</script>
@endpush
