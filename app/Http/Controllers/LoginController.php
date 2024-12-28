namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the user exists
        $user = User::where('email', $request->email)->first();

        // If user exists and the password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            // Issue a token
            $token = $user->createToken('YourAppName')->plainTextToken;

            return response()->json([
                'token' => $token
            ]);
        }

        // If credentials are invalid, return unauthorized response
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}

