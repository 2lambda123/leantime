<?php

namespace Leantime\Domain\Menu\Composers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Leantime\Core\Frontcontroller as FrontcontrollerCore;
use Leantime\Core\Composer;
use Leantime\Domain\Help\Services\Helper;
use Leantime\Domain\Notifications\Services\Notifications as NotificationService;
use Leantime\Domain\Timesheets\Services\Timesheets as TimesheetService;
use Leantime\Domain\Users\Services\Users as UserService;
use Leantime\Domain\Auth\Services\Auth as AuthService;

/**
 *
 */
class HeadMenu extends Composer
{
    public static array $views = [
        'menu::headMenu',
    ];

    private NotificationService $notificationService;
    private TimesheetService $timesheets;
    private UserService $userService;
    private AuthService $authService;
    private Helper $helperService;

    /**
     * @param NotificationService $notificationService
     * @param TimesheetService    $timesheets
     * @param UserService         $userService
     * @param AuthService         $authService
     * @return void
     */
    public function init(
        NotificationService $notificationService,
        TimesheetService $timesheets,
        UserService $userService,
        AuthService $authService,
        Helper $helperService
    ): void {
        $this->notificationService = $notificationService;
        $this->timesheets = $timesheets;
        $this->userService = $userService;
        $this->authService = $authService;
        $this->helperService = $helperService;
    }

    /**
     * @return array
     */
    /**
     * @return array
     * @throws BindingResolutionException
     */
    public function with(): array
    {
        $notificationService = $this->notificationService;
        $notifications = array();
        $newnotificationCount = 0;
        if (isset($_SESSION['userdata'])) {
            $notifications = $notificationService->getAllNotifications($_SESSION['userdata']['id']);
            $newnotificationCount = $notificationService->getAllNotifications($_SESSION['userdata']['id'], true);
        }

        $nCount = is_array($newnotificationCount) ? count($newnotificationCount) : 0;
        $totalNotificationCount =
        $totalMentionCount =
        $totalNewMentions =
        $totalNewNotifications = 0;

        foreach ($notifications as $notif) {
            if ($notif['type'] == 'mention') {
                $totalMentionCount++;
                if ($notif['read'] == 0) {
                    $totalNewMentions++;
                }
            } else {
                $totalNotificationCount++;
                if ($notif['read'] == 0) {
                    $totalNewNotifications++;
                }
            }
        }

        $user = false;
        if (isset($_SESSION['userdata'])) {
            $user = $this->userService->getUser($_SESSION['userdata']['id']);
        }

        if (!$user) {
            $this->authService->logout();
            FrontcontrollerCore::redirect(BASE_URL . '/auth/login');
        }

        $modal = $this->helperService->getHelperModalByRoute(FrontcontrollerCore::getCurrentRoute());

        return [
            'newNotificationCount' => $nCount,
            'totalNotificationCount' => $totalNotificationCount,
            'totalMentionCount' => $totalMentionCount,
            'totalNewMentions' => $totalNewMentions,
            'totalNewNotifications' => $totalNewNotifications,
            'notifications' => $notifications ?? [],
            'onTheClock' => isset($_SESSION['userdata']) ? $this->timesheets->isClocked($_SESSION["userdata"]["id"]) : false,
            'activePath' => FrontcontrollerCore::getCurrentRoute(),
            'action' => FrontcontrollerCore::getActionName(),
            'module' => FrontcontrollerCore::getModuleName(),
            'user' => $user ?? [],
            'modal' => $modal,
        ];
    }
}
