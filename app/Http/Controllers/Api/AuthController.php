<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    //inscription
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lastname' => 'required_if:role,client|string|max:255',
            'firstname' => 'required_if:role,client|string|max:255',
            'role' => 'required|in:client,super-admin,admin,user',
            'email' => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'email',
                'max:255',
                'unique:users'
            ],
            'password' => [
                "required",
                "min:6",
                "regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%?&#^_;:,])[A-Za-z\d@$!%*?&#^_;:,]{6,}$/",
                "confirmed"
            ],
        ], [
            'lastname.required_if' => 'Le nom de famille est requis.',
            'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'lastname.max' => 'Le nom de famille ne doit pas dépasser 255 caractères.',

            'firstname.required_if' => 'Le prénom est requis.',
            'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstname.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'role.required' => 'Le rôle est requis.',
            'role.in' => 'Le rôle doit être l\'un des suivants : client, super-admin, admin, user.',

            'email.required' => 'L\'email est requis.',
            'email.regex' => 'L\'email doit être une adresse email valide.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.max' => 'L\'email ne doit pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',

            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins 6 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {

            //créer un utilisateur
            $user = User::create([
                'lastname' => isset($request->lastname) ? $request->lastname : null,
                'firstname' => isset($request->firstname) ? $request->firstname : null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            //$user->assignRole($request->role);

            //$vericationUrl = URL::temporarySignedRoute('verifyEmail', Carbon::now()->addMinute(60), ['id' => $user->id, 'hash' => sha1($request->email)]);

            //Mail::to($user['email'], $user['firstname'])->send(new VerifyEmail($vericationUrl));

            /* Mail::send('mailConfirm', ['verificationUrl' => $vericationUrl, 'name' => isset($data['firstname']) ? $data['firstname'] : explode('@', $data['email'])[0]], function ($message) use ($data) {
                $config = config('mail');
                $message->subject('Verification de votre mail')
                    ->from($config['from']['address'], $config['from']['name'])
                    ->to($data['email'], isset($data['firstname']) ? $data['firstname'] : explode('@', $data['email'])[0]);
            }); */

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie !',
                'access_token' => $token,
                'data' => $user,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }

    //verification de mail
    public function verify(Request $request, $id, $hash)
    {
        try {
            //code...
            $user = User::find($id);

            if (!hash_equals((string) $hash, sha1($user['email']))) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            if (!$request->hasValidSignature()) {
                return response()->json(['message' => 'Verication link has expired ! Please click resend link.'], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email already verified'], 200);
            }

            $user->update([
                'email_verified' => true,
                'email_verified_at' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Email verified successfully.'], 200);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    //renvoie du mail de confirmation
    public function emailResend(Request $request)
    {
        try {
            //code...
            $user = $request->user();

            $vericationUrl = URL::temporarySignedRoute('verifyEmail', Carbon::now()->addMinute(60), ['id' => $user->id, 'hash' => sha1($request->email)]);

            Mail::send(
                'mailConfirm',
                ['verificationUrl' => $vericationUrl, 'name' => $user->firstname],
                function ($message) use ($user) {
                    $config = config('mail');
                    $message->subject('Verification de votre mail')
                        ->from($config['from']['address'], $config['from']['name'])
                        ->to($user['email'], $user['firstname']);
                }
            );
            return response()->json(['message' => 'Verification link sent!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue au niveau du serveur' . $e]);
        }
    }

    //connexion
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => [
                "required",
                "min:6",
                "regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%?&#^_;:,])[A-Za-z\d@$!%*?&#^_;:,]{6,}$/",
            ],
        ], [
            'email.required' => 'L\'email est requis.',
            'email.regex' => 'L\'email fourni n\'est pas valide.',

            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins 6 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            //code...
            $credentials = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            if ($credentials) {
                $user = User::where('email', $request->email)->firstOrFail();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'data' => $user,
                    'message' => 'Connecté avec succès.',
                    'access_token' => $token,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Les informations d\'identification fournies sont incorrectes.'
            ], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }

    // connexion par reseaux soxiaux
    /* public function socialLogin(Request $request)
    {
        $provider = $request->provider;

        if (!in_array($provider, ['facebook', 'google', 'twitter', 'instagram'])) {
            return response()->json(['error' => 'Invalid provider'], 400);
        }

        $socialUser = Socialite::driver($provider)->userFromToken($request->token);

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'first_name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'role' => 'client',
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    } */

    //mot de passe oublié
    public function forgotPassword(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
                ],
            ], [
                'email.required' => 'L\'email est requis.',
                'email.regex' => 'L\'email fourni n\'est pas valide.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Envoi du lien de réinitialisation
            $user = User::where('email', $request->email);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas de compte avec cet email'
                ], 404);
            }

            $resetLink = 'http://localhost:3000/resetPassword/' . sha1($request->email);

            Mail::send(
                'mailReset',
                ['resetLink' => $resetLink, 'name' => explode('@', $data['email'])[0]],
                function ($message) use ($data) {
                    $config = config('mail');
                    $message->subject('Réinitialisation de mot de passe')
                        ->from($config['from']['address'], $config['from']['name'])
                        ->to($data['email']);
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Lien de réinitialisation envoyé à votre adresse électronique.'
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur' . $e], 500);
        }
    }

    //reset password
    public function resetPassword(Request $request, $hash)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'password' => [
                    'confirmed',
                    'required',
                    'min:6',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/',
                ],
            ], [
                'password.required' => 'Le mot de passe est requis.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
                'password.min' => 'Le mot de passe doit comporter au moins 6 caractères.',
                'password.regex' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = User::whereRaw('SHA1(email) = ?', [$hash])->first();

            if ($user) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);

                $credentials = Auth::attempt(['email' => $user->email, 'password' => $request->password]);
                if ($credentials) {
                    $token = $user->createToken('auth_token')->plainTextToken;

                    return response()->json([
                        'success' => true,
                        'data' => $user,
                        'message' => 'Réinitialisation du mot de passe réussie.',
                        'access_token' => $token,
                    ], 200);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Échec de la réinitialisation du mot de passe. Veuillez réessayer.'
            ], 400);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }

    // change password
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:8',
                'new_password' => [
                    'required',
                    'confirmed',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                ],
            ], [
                'old_password.required' => 'L\'ancien mot de passe est requis.',
                'old_password.min' => 'L\'ancien mot de passe doit comporter au moins 6 caractères.',

                'new_password.required' => 'Le nouveau mot de passe est requis.',
                'new_password.min' => 'Le nouveau mot de passe doit comporter au moins 8 caractères.',
                'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
                'new_password.regex' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = User::where('id', Auth::user()->id);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Le mot de passe actuel est incorrect'], 400);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json(['message' => 'Le mot de passe a été modifié avec succès']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur' . $e], 500);
        }
    }

    //deconnexion
    public function logout()
    {
        try {
            //supprimer le token à la déconnexion
            $user = Auth::user();

            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie.'
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur' . $e], 500);
        }
    }

    //mise à jour des informations utilisateurs
    public function updateInfo(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'lastname' => 'required_if:role,client|string|max:255',
            'firstname' => 'required_if:role,client|string|max:255',
            'email' => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'email',
                'max:255',
            ],
            //'birthday' => 'nullable|date_format:d.m.Y',
            'phone' => 'nullable|string|max:15',
            //'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ], [
            'lastname.required_if' => 'Le nom de famille est requis.',
            'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'lastname.max' => 'Le nom de famille ne doit pas dépasser 255 caractères.',

            'firstname.required_if' => 'Le prénom est requis.',
            'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstname.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'email.required' => 'L\'email est requis.',
            'email.regex' => 'L\'email doit être une adresse email valide.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.max' => 'L\'email ne doit pas dépasser 255 caractères.',

            //'birthday.date_format' => 'La date de naissance doit être au format dd.mm.yyyy.',

            'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 15 caractères.',

            //'country.string' => 'Le pays doit être une chaîne de caractères.',

            'address.string' => 'L\'adresse doit être une chaîne de caractères.',

            'city.string' => 'La ville doit être une chaîne de caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            //code...
            $user = User::where('id', Auth::user()->id)->firstOrFail();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié.',
                ], 401);
            }

            $user->update([
                'firstname' => $request->input('firstname') ?? $user->firstname,
                'lastname' => $request->input('lastname') ?? $user->lastname,
                'phone' => $request->input('phone') ?? $user->phone,
                //'country' => $request->input('country') ?? $user->country,
                'address' => $request->input('address') ?? $user->address,
                'city' => $request->input('city') ?? $user->city,
                'birthday' => $request->input('birthday') ?? $user->birthday,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Les informations de l\'utilisateur ont été mises à jour avec succès.',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'avatar.image' => 'La photo de profil doit être une image valide.',
            'avatar.mimes' => 'La photo de profil doit être au format jpeg, png, jpg, gif ou svg.',
            'avatar.max' => 'La photo de profil ne doit pas dépasser 2 Mo.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $user = User::where('id', Auth::user()->id)->firstOrFail();

            if ($request->hasFile('avatar')) {
                if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                    Storage::delete('public/' . $user->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = asset('storage/' . $avatarPath);
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Photo de profil mise à jour avec succès.',
                    'data' => $user,
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }
}
