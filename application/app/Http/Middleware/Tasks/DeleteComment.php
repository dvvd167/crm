<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [delete] precheck processes for tasks
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Tasks;
use App\Models\Task;
use App\Permissions\CommentPermissions;
use Closure;
use Log;

class DeleteComment {

    /**
     * The permisson repository instance.
     */
    protected $commentpermissions;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(CommentPermissions $commentpermissions) {

        //permissions
        $this->commentpermissions = $commentpermissions;

    }

    /**
     * Check user permissions to edit a task
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //attachement id
        $comment_id = $request->route('commentid');

        //does the task exist
        if (!$comment = \App\Models\Comment::Where('comment_id', $comment_id)->first()) {
            Log::error("comment could not be found", ['process' => '[permissions][tasks][delete-comment]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'comment id' => $comment_id ?? '']);
            //just return
            return response()->json([]);
        }

        //check permissions
        if ($comment->commentresource_type == 'task') {
            if ($this->commentpermissions->check('delete', $comment_id)) {
                return $next($request);
            }
        }

        //no items were passed with this request
        Log::error("permission denied", ['process' => '[permissions][tasks][delete-comment]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'comment id' => $comment_id ?? '']);
        abort(403);
    }
}