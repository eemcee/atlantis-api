<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function addComment(Request $request)
    {
        try {
            DB::table('comments')
                ->insert([
                    'username' => 'Anonymous'. $this->generateRandom4DigitNumber(),
                    'comments' => $request->comment,
                    'created_dt' => date('Y-m-d H:i:s')
                ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for submitting your comment. Your comment will be displayed as soon as Administrators approve.',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred. Please re-submit.',
                'data' => []
            ], 500);
        }


    }

    private function generateRandom4DigitNumber(): int
    {
        // Generate a random number between 1000 and 9999
        return rand(1000, 9999);
    }

    public function getComments()
    {
        return DB::table('comments')->get();
    }

    public function approve($id, $type)
    {
        try {
            DB::table('comments')
                ->where('id', $id)
                ->update([
                    'is_approve' => $type,
                    'approved_by' => date('Y-m-d H:i:s')
                ]);

            return response()->json(['message' => 'Updated successfully.', 'status' => 'success'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
