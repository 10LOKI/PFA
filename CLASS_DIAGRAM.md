# ActTogether v2.0 — UML Class Diagram

**Version:** 2.0  
**Last Updated:** 2026-04-26  
**Scope:** Full application architecture (Models, Controllers, Services, Policies)

---

## Legend & Notation

| Symbol | Meaning |
|--------|---------|
| `+` | Public method/property |
| `-` | Private method/property |
| `#` | Protected method/property |
| `<<interface>>` | Interface |
| `<<trait>>` | PHP Trait |
| `<<enum>>` | PHP Enum |
| `{abstract}` | Abstract class |
| `-->` | Association |
| `--|>` | Inheritance/Extends |
| `--*` | One-to-Many |
| `--0..1` | Zero-or-One |
| `--0..*` | Zero-or-Many |
| `--1..*` | One-or-Many |

**Stereotypes:**
- `<<model>>` Eloquent Model
- `<<controller>>` HTTP Controller
- `<<action>>` Service/Use-Case class
- `<<policy>>` Authorization policy
- `<<event>>` Broadcastable event
- `<<mailable>>` Email class
- `<<request>>` Form request validator

**Key Colors:**
- 🟦 **Blue** — Models (Database entities)
- 🟩 **Green** — Controllers (HTTP layer)
- 🟨 **Yellow** — Actions (Business logic)
- 🟧 **Orange** — Policies/Pivot tables
- 🟥 **Red** — Supporting (Events, Mail, Requests)

---

## Table of Contents

1. [Core Domain Models](#core-domain-models)
2. [Pivot Models](#pivot-models)
3. [Controllers](#controllers)
4. [Actions (Service Layer)](#actions-service-layer)
5. [Policies](#policies)
6. [Broadcastable Events](#broadcastable-events)
7. [Mailables](#mailables)
8. [Form Requests](#form-requests)
9. [Spatie Permission Integration](#spatie-permission-integration)
10. [Database Triggers](#database-triggers)
11. [Full Relationship Map](#full-relationship-map)

---

## 1. Core Domain Models

### User 🟦

```
<<model>>
class User {
    +id: int
    +name: string
    +email: string
    +password: string
    +role: enum<'student','partner','admin'>
    +avatar: string|null
    +city: string|null
    +phone: string|null
    +establishment_id: int|null
    +points_balance: int
    +total_hours: int
    +grade: enum<'novice','pilier','ambassadeur'>
    +kyc_verified: bool
    +is_certified_partner: bool
    +interests: json
    +email_verified_at: datetime|null
    +created_at: datetime
    +updated_at: datetime
    +deleted_at: datetime|null

    +isAdmin(): bool
    +isPartner(): bool
    +isStudent(): bool
    +hasRole(string): bool
    +forceDelete(): LogicException
    +restore(): LogicException
    +booted(): void
    +routeNotificationForMail(): string

    --hidden--
    password
    remember_token

    --casts--
    email_verified_at: datetime
    password: hashed
    kyc_verified: boolean
    is_certified_partner: boolean
    interests: array
}
```

**Relations:**
- `User --0..1 --> Establishment` (belongsTo)
- `User --0..1 --> Partner` (hasOne)
- `User --0..* --> Grade` (hasMany)
- `User --0..* --> PointsTransaction` (hasMany)
- `User --0..* --> RewardRedemption` (hasMany)
- `User --0..* --> Event hostedEvents` (hasMany as partner)
- `User --0..* --> Comment` (hasMany)
- `User --0..* --> Feedback` (hasMany)
- `User --0..* --> Notification customNotifications` (hasMany)
- `User --0..* --> Event participants` (belongsToMany via `event_user`)
- `User --0..* --> Conversation conversations` (belongsToMany via `conversation_user`)
- `User --0..* --> Message sentMessages` (hasMany)
- `User --0..* --> Like likedEvents` (morphToMany)
- `User --0..* --> Notification notifications` (morphToMany via `notification_usages`)

**Traits:** HasFactory, HasPermissions (Spatie), HasRoles (Spatie), Notifiable

---

### Event 🟦

```
<<model>>
class Event {
    +id: int
    +partner_id: int
    +title: string
    +description: text
    +category: string|null
    +city: string
    +address: string
    +latitude: decimal(7)
    +longitude: decimal(7)
    +starts_at: datetime
    +ends_at: datetime
    +volunteer_quota: int
    +duration_hours: decimal
    +points_reward: int
    +urgency_multiplier: decimal(2)
    +status: enum<'pending','approved','rejected','cancelled'>
    +image: string|null
    +created_at: datetime
    +updated_at: datetime
    +deleted_at: datetime|null

    +isApproved(): bool
    +isFull(): bool
    +effectivePoints(): int
    +isLikedBy(?User): bool
    +getLikesCountAttribute(): int
    +forceDelete(): LogicException
    +restore(): LogicException
}
```

**Relations:**
- `Event --1 --> User partner` (belongsTo)
- `Event --0..* --> User participants` (belongsToMany via `event_user`)
- `Event --0..* --> Comment` (morphMany)
- `Event --0..* --> Feedback` (morphMany)
- `Event --0..* --> Like` (morphMany)
- `Event --0..* --> User likedBy` (morphToMany)
- `Event --0..* --> PointsTransaction` (morphMany via source)

**Traits:** HasFactory

---

### Partner 🟦

```
<<model>>
class Partner {
    +id: int
    +user_id: int (unique)
    +company_name: string
    +logo: string|null
    +bio: text|null
    +website: string|null
    +sector: string|null
    +rc_number: string|null
    +rc_document: string|null
    +kyc_status: enum<'pending','approved','rejected'>
    +is_certified: bool
    +created_at: datetime
    +updated_at: datetime

    +isApproved(): bool
}
```

**Relations:**
- `Partner --1 --> User user` (belongsTo)
- `Partner --0..* --> Event events` (hasMany)

**Traits:** HasFactory

---

### Partener 🟦 (Deprecated)

```
<<model>>
class Partener {
    // Empty stub - table renamed to "partners"
    // Should be deprecated/removed
}
```

---

### Establishment 🟦

```
<<model>>
class Establishment {
    +id: int
    +name: string
    +type: enum<'school','university','etc'>
    +address: string
    +city: string
    +phone: string|null
    +email: string|null
    +created_at: datetime
    +updated_at: datetime

    --relations--
    +users(): HasMany(User)
    +grades(): HasMany(Grade)
}
```

**Relations:**
- `Establishment --0..* --> User users` (hasMany)
- `Establishment --0..* --> Grade grades` (hasMany)

**Traits:** HasFactory

---

### Grade 🟦

```
<<model>>
class Grade {
    +id: int
    +user_id: int
    +establishment_id: int
    +level: string
    +field: string
    +academic_year: string
    +created_at: datetime
    +updated_at: datetime

    --relations--
    +user(): BelongsTo(User)
    +establishment(): BelongsTo(Establishment)
}
```

**Relations:**
- `Grade --1 --> User user` (belongsTo)
- `Grade --1 --> Establishment establishment` (belongsTo)

**Traits:** HasFactory

---

### Reward 🟦

```
<<model>>
class Reward {
    +id: int
    +partner_id: int
    +title: string
    +description: text
    +image: string|null
    +points_cost: int
    +stock: int|null (null = unlimited)
    +min_grade: enum<'novice','pilier','ambassadeur'>
    +is_premium: bool
    +is_active: bool
    +expires_at: datetime|null
    +created_at: datetime
    +updated_at: datetime

    +isAvailable(): bool
    +isAccessibleBy(User): bool
}
```

**Relations:**
- `Reward --1 --> User partner` (belongsTo)
- `Reward --0..* --> RewardRedemption redemptions` (hasMany)
- `Reward --0..* --> PointsTransaction pointsTransactions` (morphMany via source)

**Traits:** HasFactory

---

### RewardRedemption 🟦

```
<<model>>
class RewardRedemption {
    +id: int
    +user_id: int
    +reward_id: int
    +points_spent: int
    +status: string ('pending','approved','rejected')
    +redeemed_at: datetime
    +created_at: datetime
    +updated_at: datetime

    +isPending(): bool
    +isApproved(): bool
    +isRejected(): bool
}
```

**Relations:**
- `RewardRedemption --1 --> User user` (belongsTo)
- `RewardRedemption --1 --> Reward reward` (belongsTo)
- `RewardRedemption --0..* --> PointsTransaction pointsTransactions` (morphMany)

**Traits:** HasFactory

---

### PointsTransaction 🟦

```
<<model>>
class PointsTransaction {
    +id: int
    +user_id: int
    +type: enum<'earned','spent','burned','adjusted'>
    +amount: int
    +balance_after: int
    +source_type: string|null (morph)
    +source_id: int|null (morph)
    +description: string|null
    +created_at: datetime
    --updated_at: null (disabled)--

    +boot(): void (prevents update/delete)
    +source(): MorphTo
}
```

**Relations:**
- `PointsTransaction --1 --> User user` (belongsTo)
- `PointsTransaction --0..1 --> morph source` (MorphTo) → Event, Reward, or null

**Traits:** HasFactory  
**Note:** Immutable ledger; UPDATED_AT = null

---

### Notification 🟦 (Custom)

```
<<model>>
class Notification {
    +id: int
    +user_id: int
    +type: string
    +title: string
    +message: text
    +link: string|null
    +event_id: int|null
    +read: bool
    +created_at: datetime
    +updated_at: datetime

    --relations--
    +user(): BelongsTo(User)
}
```

**Relations:**
- `Notification --1 --> User user` (belongsTo)

**Note:** Custom notification table, NOT Laravel's default `notifications` table

---

### Like 🟦

```
<<model>>
class Like {
    +id: int
    +user_id: int
    +likeable_id: int
    +likeable_type: string (morph)
    +created_at: datetime

    --unique--
    (user_id, likeable_id, likeable_type)

    --index--
    (likeable_id, likeable_type)

    --relations--
    +user(): BelongsTo(User)
    +likeable(): MorphTo (typically Event)
}
```

**Relations:**
- `Like --1 --> User user` (belongsTo)
- `Like --1 --> morph likeable` (MorphTo)

**Traits:** HasFactory

---

### Comment 🟦

```
<<model>>
class Comment {
    +id: int
    +user_id: int
    +commentable_id: int
    +commentable_type: string (morph)
    +body: text
    +created_at: datetime
    +updated_at: datetime

    --relations--
    +user(): BelongsTo(User)
    +commentable(): MorphTo (typically Event)
}
```

**Relations:**
- `Comment --1 --> User user` (belongsTo)
- `Comment --1 --> morph commentable` (MorphTo)

**Traits:** HasFactory

---

### Feedback 🟦

```
<<model>>
class Feedback {
    +id: int
    +user_id: int
    +feedbackable_id: int
    +feedbackable_type: string (morph)
    +rating: int (1-5)
    +message: text|null
    +created_at: datetime
    +updated_at: datetime

    +isValidRating(): bool
}
```

**Relations:**
- `Feedback --1 --> User user` (belongsTo)
- `Feedback --1 --> morph feedbackable` (MorphTo)

**Traits:** HasFactory

---

### Conversation 🟦

```
<<model>>
class Conversation {
    +id: int
    +created_at: datetime
    +updated_at: datetime

    --relations--
    +participants(): BelongsToMany(User, conversation_user)
    +messages(): HasMany(Message)
    +latestMessage(): HasOne(Message)->latestOfMany()

    +isParticipant(User): bool
}
```

**Relations:**
- `Conversation --0..* --> User participants` (belongsToMany via `conversation_user`)
- `Conversation --0..* --> Message messages` (hasMany)

---

### Message 🟦

```
<<model>>
class Message {
    +id: int
    +conversation_id: int
    +sender_id: int
    +body: text
    +read_at: datetime|null
    +created_at: datetime
    +updated_at: datetime

    +markAsRead(): void
}
```

**Relations:**
- `Message --1 --> Conversation conversation` (belongsTo)
- `Message --1 --> User sender` (belongsTo)

---

## 2. Pivot Models

### EventUser 🟧 (Pivot Model)

```
<<model>>
class EventUser extends Pivot {
    +id: int
    +event_id: int
    +user_id: int
    +status: enum<'registered','checked_in','absent','cancelled','wishlist'>
    +checked_in_at: datetime|null
    +checked_out_at: datetime|null
    +points_earned: int
    +partner_rating: int|null
    +partner_feedback: text|null
    +qr_token: string (unique)
    +created_at: datetime
    +updated_at: datetime

    +hasCheckedIn(): bool
    +hasCheckedOut(): bool
    +hasSavedToWishlist(): bool
    +actualDurationInMinutes(): int
    +proRatedHours(): float
    +booted(): void (generates qr_token)
}
```

**Unique:** (event_id, user_id)  
**Relations:**
- `EventUser --1 --> Event event` (belongsTo)
- `EventUser --1 --> User user` (belongsTo)

---

### ConversationUser 🟧 (Pivot)

```
<<pivot>>
class ConversationUser extends Pivot {
    +id: int
    +conversation_id: int
    +user_id: int
    +created_at: datetime

    --unique--
    (conversation_id, user_id)
}
```

**Relations:**
- `ConversationUser --1 --> Conversation conversation` (belongsTo)
- `ConversationUser --1 --> User user` (belongsTo)

---

## 3. Controllers 🟩

### Base Controller 🟩

```
<<controller>>
class Controller {
    +authorizesRequests(): UsesAuthorizesRequests trait
}
```

All controllers extend this base.

---

### EventController 🟩

```
<<controller>>
class EventController {
    --methods--
    +index(Request): View (filters by role, search, paginate 12)
    +show(Event): View (loads participants, pivot)
    +create(): View (authorize partner)
    +store(Request): Redirect (validates, creates, image, notification)
    +edit(Event): View
    +update(Request, Event): Redirect
    +destroy(Event): Redirect (cancels, not hard-delete)
    +approve(Event): Redirect (admin only)
    +reject(Event): Redirect (admin only)

    --dependencies--
    Event, User, Notification, EventCreatedNotification
    EventPolicy
}
```

Routes:
- `GET /events` → index
- `GET /events/create` → create
- `POST /events` → store
- `GET /events/{event}` → show
- `POST /events/{event}` → update
- `DELETE /events/{event}` → destroy
- `POST /events/{event}/approve` → approve
- `POST /events/{event}/reject` → reject

---

### EventRegistrationController 🟩

```
<<controller>>
class EventRegistrationController {
    --methods--
    +store(Event): Redirect (register with QR token)
    +destroy(Event): Redirect (cancel registration)

    --dependencies--
    StudentEventQrMail (Mailable)
    Gate 'event.register'
}
```

Routes:
- `POST /events/{event}/register` → store
- `DELETE /events/{event}/register` → destroy

---

### StudentCheckInController 🟩 (Invokable)

```
<<controller>>
class StudentCheckInController {
    +__invoke(Request, string $token): Redirect (scan QR check-in)
}
```

Route: `GET /checkin/scan/{token}`

---

### CheckOutController 🟩

```
<<controller>>
class CheckOutController {
    --constructor--
    CheckOutStudentAction $checkOutAction

    --methods--
    +store(Request, Event): Redirect (partner/admin validate checkout)
}
```

Route: `POST /events/{event}/checkout`

---

### RewardController 🟩

```
<<controller>>
class RewardController {
    --methods--
    +index(): View (active rewards list)
    +redeem(Reward): Redirect (uses RedeemRewardAction)
    +create(): View (partner only)
    +store(Request): Redirect (create new reward)
    +edit(Reward): View
    +update(Request, Reward): Redirect
    +destroy(Reward): Redirect (delete)

    --dependencies--
    RedeemRewardAction
    RewardPolicy
}
```

Routes:
- `GET /rewards` → index
- `POST /rewards/{reward}/redeem` → redeem
- `GET /rewards/create` → create
- `POST /rewards` → store
- `GET /rewards/{reward}/edit` → edit
- `PUT/PATCH /rewards/{reward}` → update
- `DELETE /rewards/{reward}` → destroy

---

### WalletController 🟩

```
<<controller>>
class WalletController {
    --methods--
    +index(Request): View (transaction history)
}
```

Route: `GET /wallet`

---

### LikeController 🟩

```
<<controller>>
class LikeController {
    --constructor--
    LikeEventAction $likeEvent
    UnlikeEventAction $unlikeEvent

    --methods--
    +store(Event): Redirect
    +destroy(Event): Redirect
    +index(): View (my likes)
}
```

Routes:
- `POST /events/{event}/like` → store
- `DELETE /events/{event}/like` → destroy
- `GET /likes` → index

---

### MessageController 🟩

```
<<controller>>
class MessageController {
    --methods--
    +index(): View (conversations list)
    +show(Conversation): View (read messages)
    +store(Request): Redirect (send message)
    +start(Request): Redirect (create/get conversation)

    --dependencies--
    MessageSent (event)
}
```

Routes:
- `GET /messages` → index
- `GET /conversations/{conversation}` → show
- `POST /messages` → store
- `GET /conversations/start/{user}` → start

---

### NotificationController 🟩

```
<<controller>>
class NotificationController {
    --methods--
    +index(): Json (last 50)
    +markAsRead(int): Json
    +markAllAsRead(): Json
    +unreadCount(): Json

    --dependencies--
    Notification model
}
```

Routes (API):
- `GET /notifications` → index
- `POST /notifications/{id}/read` → markAsRead
- `POST /notifications/read-all` → markAllAsRead
- `GET /notifications/unread-count` → unreadCount

---

### LeaderboardController 🟩

```
<<controller>>
class LeaderboardController {
    --methods--
    +index(): View (national all-time)
    +city(string $city): View (city-based)
    +establishation(int $id): View (school-based)
    +weekly(): View (current week points)
}
```

Routes:
- `GET /leaderboard` → index
- `GET /leaderboard/city/{city}` → city
- `GET /leaderboard/establishment/{id}` → establishment
- `GET /leaderboard/weekly` → weekly

---

### WishlistController 🟩

```
<<controller>>
class WishlistController {
    --methods--
    +store(Event): Redirect (add to wishlist)
    +destroy(Event): Redirect (remove from wishlist)
}
```

Routes:
- `POST /events/{event}/wishlist` → store
- `DELETE /events/{event}/wishlist` → destroy

---

### UserController 🟩

```
<<controller>>
class UserController {
    --methods--
    +index(Request): View (search users)
}
```

Route: `GET /users`

---

### ProfileController 🟩

```
<<controller>>
class ProfileController {
    --methods--
    +edit(Request): View
    +update(ProfileUpdateRequest): Redirect
    +destroy(Request): Redirect (delete account)
}
```

Routes (Breeze):
- `GET /user/profile` → edit
- `PATCH /user/profile` → update
- `DELETE /user/profile` → destroy

---

### DashboardController 🟩

```
<<controller>>
class DashboardController {
    --methods--
    +index(): View (route to role-specific)
    +student(): View
    +partner(): View
    +admin(): View
}
```

Route: `GET /dashboard`

---

### RegisteredUserController 🟩 (Auth)

```
<<controller>>
class RegisteredUserController {
    --methods--
    +create(): View (registration form)
    +store(Request): Redirect (creates user, Partner profile if partner)

    --dependencies--
    RegisterUserAction
    Registered event
}
```

Routes (Breeze):
- `GET /register` → create
- `POST /register` → store

---

### Admin\KycController 🟩

```
<<controller>>
class KycController {
    --methods--
    +index(): View (pending partners)
    +approve(Partner): Redirect
    +reject(Partner): Redirect

    --dependencies--
    PartnerPolicy
    Gates: 'view', 'approve', 'reject'
}
```

Routes (Admin):
- `GET /admin/kyc` → index
- `POST /admin/kyc/approve/{partner}` → approve
- `POST /admin/kyc/reject/{partner}` → reject

---

### Breeze Auth Controllers 🟩

Standard Laravel Breeze controllers (no modifications noted):
- VerifyEmailController
- PasswordResetLinkController
- PasswordController
- EmailVerificationPromptController
- NewPasswordController
- EmailVerificationNotificationController
- AuthenticatedSessionController
- ConfirmablePasswordController

---

## 4. Actions (Service Layer) 🟨

### RegisterUserAction 🟨

```
<<action>>
class RegisterUserAction {
    +PERMISSIONS: array (role => [permissions])
    +execute(Request $data): User

    --logic--
    Transactionally creates User
    Assigns spatie permissions based on role:
      - student: event.browse, event.register, ...
      - partner: event.browse, event.create, event.update, ...
}
```

**Used by:** RegisteredUserController

---

### CreditPointsAction 🟨

```
<<action>>
class CreditPointsAction {
    +execute(User $user, int $amount, string $type, string $description, ?Model $source = null): PointsTransaction

    --logic--
    Within transaction:
      - Creates PointsTransaction record
      - Database trigger auto-updates user.points_balance
    Types: earned, spent, burned, adjusted
}
```

**Used by:** CheckOutStudentAction, RedeemRewardAction

---

### CheckOutStudentAction 🟨

```
<<action>>
class CheckOutStudentAction {
    --constructor--
    CreditPointsAction $creditPoints
    UpgradeGradeAction $upgradeGrade

    +execute(Event $event, User $student): void

    --logic--
    Transactionally:
      1. Validates EventUser pivot (must be checked_in, not already checked_out)
      2. Sets checked_out_at = now()
      3. Calculates proRatedHours = min(actualHours, event.duration_hours) [0 if <30min]
      4. Calculates points = round(proRatedHours × event.effectivePoints())
      5. If points > 0:
         - Updates pivot.points_earned
         - Credits PointsTransaction (type='earned')
         - Increments user.total_hours
         - Upgrades user grade via UpgradeGradeAction
}
```

**Used by:** CheckOutController

---

### GenerateStudentQrAction 🟨

```
<<action>>
class GenerateStudentQrAction {
    +execute(EventUser $registration): string (SVG)

    --logic--
    - Ensures qr_token exists (UUID)
    - Generates QR code: route('checkin.scan', token)
    - Uses bacon/bacon-qr-code package
    - Returns 300px SVG
}
```

**Used by:** StudentEventQrMail

---

### LikeEventAction 🟨

```
<<action>>
class LikeEventAction {
    +execute(Event $event, User $user): Like

    --logic--
    - Checks for existing like (guard)
    - Creates Like record transactionally
}
```

---

### UnlikeEventAction 🟨

```
<<action>>
class UnlikeEventAction {
    +execute(Event $event, User $user): bool

    --logic--
    - Finds existing Like
    - Deletes transactionally
    - Returns deletion result
}
```

---

### RedeemRewardAction 🟨

```
<<action>>
class RedeemRewardAction {
    --constructor--
    CreditPointsAction $creditPoints

    +execute(User $user, Reward $reward): RewardRedemption

    --guards--
    - Reward.isAvailable()
    - User grade >= reward.min_grade
    - User points_balance >= reward.points_cost

    --logic--
    Transactionally:
      1. Credits PointsTransaction (amount = -points_cost, type='burned')
      2. Decrements reward stock (if limited)
      3. Creates RewardRedemption (status='pending', redeemed_at=now())
}
```

**Used by:** RewardController

---

### UpgradeGradeAction 🟨

```
<<action>>
class UpgradeGradeAction {
    const GRADE_HIERARCHY = ['novice', 'pilier', 'ambassadeur']
    const THRESHOLDS = ['novice' => 0, 'pilier' => 50, 'ambassadeur' => 150]

    +execute(User $student): bool (true if upgraded)
    +getThreshold(string): int
    +getNextThreshold(string): ?int

    --logic--
    - Compares user.total_hours to next grade threshold
    - Upgrades if threshold met
}
```

**Used by:** CheckOutStudentAction

---

## 5. Policies 🟧

### EventPolicy 🟧

```
<<policy>>
class EventPolicy {
    +viewAny(User): bool (always true)
    +view(User, Event): bool (always true)
    +create(User): bool (user.isPartner())
    +update(User, Event): bool (can('event.update') AND user.id === event.partner_id AND event.status NOT IN cancelled/rejected)
    +delete(User, Event): bool (can('event.delete') AND user.id === event.partner_id)
    +approve(User, Event): bool (user.isAdmin())
    +checkIn(User, Event): bool (user.isAdmin() OR user.id === event.partner_id)
    +checkOut(User, Event): bool (same as checkIn)
}
```

---

### RewardPolicy 🟧

```
<<policy>>
class RewardPolicy {
    +viewAny(User): bool (user.can('reward.browse'))
    +redeem(User, Reward): bool (user.can('reward.redeem') AND user.isStudent() AND reward.isAvailable() AND reward.isAccessibleBy(user))
    +create(User): bool (user.isPartner())
    +update(User, Reward): bool (user.can('reward.update') AND user.id === reward.partner_id)
    +delete(User, Reward): bool (user.can('reward.delete') AND user.id === reward.partner_id)
}
```

---

### PartnerPolicy 🟧

```
<<policy>>
class PartnerPolicy {
    +view(User, Partner): bool (user.isAdmin())
    +approve(User, Partner): bool (user.isAdmin())
    +reject(User, Partner): bool (user.isAdmin())
    +update(User, Partner): bool (user.id === partner.user_id)
}
```

---

## 6. Broadcastable Events 🔴

### EventCreated 🔴

```
<<event>>
class EventCreated implements ShouldBroadcast {
    +public Event $event
    +public int $recipientId

    +__construct(Event $event, int $recipientId): void
    +broadcastOn(): PrivateChannel (notifications.{recipientId})
    +broadcastWith(): array (event summary)
}
```

---

### MessageSent 🔴

```
<<event>>
class MessageSent implements ShouldBroadcast {
    +public Message $message

    +__construct(Message $message): void
    +broadcastOn(): PrivateChannel (conversation.{conversation_id})
    +broadcastWith(): array (message data with sender)
}
```

---

## 7. Mailables 🔴

### StudentEventQrMail 🔴

```
<<mailable>>
class StudentEventQrMail {
    +public EventUser $registration
    +string $qrSvg

    +__construct(EventUser $registration): void
    +build(): Mailable (uses emails.student-event-qr view)
}
```

Uses: GenerateStudentQrAction

---

### EventCreatedNotification 🔴 (ShouldQueue)

```
<<mailable>>
class EventCreatedNotification {
    +toMail(object $notifiable): MailMessage
    --subject includes event title--
    --action button: View Event--
}
```

---

### EventCheckInNotification 🔴 (ShouldQueue)

```
<<mailable>>
class EventCheckInNotification {
    +__construct(Event $event, User $student): void
    +toMail(object $notifiable): MailMessage (check-in confirmation)
}
```

---

### EventCheckOutNotification 🔴 (ShouldQueue)

```
<<mailable>>
class EventCheckOutNotification {
    +__construct(Event $event, User $student, int $pointsEarned): void
    +toMail(object $notifiable): MailMessage (points + total hours)
}
```

---

## 8. Form Requests 🔴

### ProfileUpdateRequest 🔴

```
<<request>>
class ProfileUpdateRequest extends FormRequest {
    +rules(): array
    --rules--
    name: required|string|max:255
    email: required|string|email|max:255|unique:users,email,{id}
    interests: nullable|array
    interests.*: string|max:100
}
```

---

### LoginRequest 🔴

```
<<request>>
class LoginRequest extends FormRequest {
    +rules(): array
    +authenticate(): void (attempts login, rate limiting)
    +ensureIsNotRateLimited(): void
    +throttleKey(): string
}
```

Rate limit: 5 attempts

---

## 9. Spatie Permission Integration 🔴

### Permission & Role Models (External Library)

```
<<external>>
Spatie\Permission\Models\Permission {
    +id: int
    +name: string
    +guard_name: string
    +created_at: timestamp
    +updated_at: timestamp

    --relations--
    +roles(): BelongsToMany(Role)
    +users(): BelongsToMany(User)
}

Spatie\Permission\Models\Role {
    +id: int
    +name: string
    +guard_name: string
    +team_foreign_key: int|null
    +created_at: timestamp
    +updated_at: timestamp

    --relations--
    +permissions(): BelongsToMany(Permission)
    +users(): BelongsToMany(User)
}
```

**Junction tables:**
- `role_has_permissions` (permission_id, role_id)
- `model_has_roles` (role_id, model_type, model_id, team_foreign_key)
- `model_has_permissions` (permission_id, model_type, model_id, team_foreign_key)

---

## 10. Database Triggers 🔴

```
<<database-trigger>>
trigger: sync_points_balance_after_insert
ON: points_transactions (AFTER INSERT)
FOR EACH ROW
BEGIN
    UPDATE users
    SET points_balance = COALESCE(SUM(
        CASE
            WHEN type IN ('earned', 'adjusted') AND amount > 0 THEN amount
            WHEN type IN ('burned', 'spent') AND amount > 0 THEN -amount
            WHEN type = 'adjusted' AND amount < 0 THEN amount
            ELSE 0
        END
    ), 0)
    WHERE id = NEW.user_id;
END

---

<<database-trigger>>
trigger: sync_points_balance_after_delete
ON: points_transactions (AFTER DELETE)
FOR EACH ROW
BEGIN
    UPDATE users
    SET points_balance = COALESCE(SUM(
        CASE
            WHEN type IN ('earned', 'adjusted') AND amount > 0 THEN amount
            WHEN type IN ('burned', 'spent') AND amount > 0 THEN -amount
            WHEN type = 'adjusted' AND amount < 0 THEN amount
            ELSE 0
        END
    ), 0)
    WHERE id = OLD.user_id;
END
```

---

## 11. Full Relationship Map

### Association Summary

```
[User] "1" ---- "0..*" [Event] (as partner)
    partner_id FK

[User] "1" ---- "0..*" [EventUser] "0..*" ---- "1" [Event]
    event_user.user_id FK → users.id
    event_user.event_id FK → events.id
    Unique: (event_id, user_id)
    Pivot attributes: status, checked_in_at, checked_out_at, points_earned, qr_token

[User] "1" ---- "0..*" [EventUser] "0..*" ---- "1" [User] (participant)
    (same pivot, inverse relationship)

[User] "1" ---- "0..*" [Partner]
    partners.user_id FK (unique, one-to-one)

[Partner] "1" ---- "0..*" [Event] (as host)
    events.partner_id FK → users.id (Partner.user_id)

[User] "1" ---- "0..*" [Grade]
    grades.user_id FK

[Establishment] "1" ---- "0..*" [User]
    users.establishment_id FK

[Establishment] "1" ---- "0..*" [Grade]
    grades.establishment_id FK

[User] "1" ---- "0..*" [PointsTransaction]
    points_transactions.user_id FK

[PointsTransaction] "0..1" ---- "0..1" [morph] (Event|Reward|null)
    source_type, source_id polymorphic

[User] "1" ---- "0..*" [RewardRedemption]
    reward_redemptions.user_id FK

[Reward] "1" ---- "0..*" [RewardRedemption]
    reward_redemptions.reward_id FK

[Reward] "1" ---- "0..*" [Partner] (owner User)
    rewards.partner_id FK → users.id

[User] "1" ---- "0..*" [Comment]
    comments.user_id FK

[Comment] "0..1" ---- "0..1" [morph] (Event)
    commentable_type, commentable_id polymorphic

[User] "1" ---- "0..*" [Feedback]
    feedbacks.user_id FK

[Feedback] "0..1" ---- "0..1" [morph] (Event)
    feedbackable_type, feedbackable_id polymorphic

[User] "1" ---- "0..*" [Like]
    likes.user_id FK

[Like] "0..1" ---- "0..1" [morph] (Event)
    likeable_type, likeable_id polymorphic
    Unique: (user_id, likeable_id, likeable_type)

[User] "1" ---- "0..*" [Notification]
    notifications.user_id FK

[User] "1" ---- "0..*" [Message] (as sender)
    messages.sender_id FK

[Conversation] "1" ---- "0..*" [Message]
    messages.conversation_id FK

[User] "0..*" ---- "0..*" [Conversation] (participants)
    conversation_user pivot table
    Unique: (conversation_id, user_id)

[User] "0..*" ---- "0..*" [Notification] (morph usages)
    notification_usages pivot (nullable, custom implementation)
```

---

## 12. Enums & Constants

### User Role 🟨

```
<<enum>>
enum UserRole: string {
    case STUDENT = 'student'
    case PARTNER = 'partner'
    case ADMIN = 'admin'
}
```

### Event Status 🟨

```
<<enum>>
enum EventStatus: string {
    case PENDING = 'pending'
    case APPROVED = 'approved'
    case REJECTED = 'rejected'
    case CANCELLED = 'cancelled'
}
```

### EventUser Status 🟨

```
<<enum>>
enum RegistrationStatus: string {
    case REGISTERED = 'registered'
    case CHECKED_IN = 'checked_in'
    case ABSENT = 'absent'
    case CANCELLED = 'cancelled'
    case WISHLIST = 'wishlist'
}
```

### Partner KYC Status 🟨

```
<<enum>>
enum KycStatus: string {
    case PENDING = 'pending'
    case APPROVED = 'approved'
    case REJECTED = 'rejected'
}
```

### User Grade 🟨

```
<<enum>>
enum UserGrade: string {
    case NOVICE = 'novice'
    case PILIER = 'pilier'
    case AMBASSADEUR = 'ambassadeur'
}
```

### Points Type 🟨

```
<<enum>>
enum PointsType: string {
    case EARNED = 'earned'
    case SPENT = 'spent'
    case BURNED = 'burned'
    case ADJUSTED = 'adjusted'
}
```

---

## 13. Layer Architecture

```
[HTTP Layer]
    └─ Controllers (green) ──┬→ Actions (yellow) ──→ Models (blue)
                            └─ Policies (orange)
    └─ Form Requests (red)
    └─ Mailable (red)
    └─ Events (red)

[Domain Layer]
    └─ Models (Eloquent ORM)
    └─ Pivot Models (Extended)

[Infrastructure Layer]
    └─ Database Triggers (PostgreSQL)
    └─ Spatie Permission tables
    └─ Broadcast channels (Pusher/Pushe)

[Service Layer]
    └─ Actions (business logic, transactional)
    └─ Policies (authorization)
```

---

## 14. Key Business Rules (Encoded in Methods)

### Points Calculation

```
Event::effectivePoints() = points_reward × urgency_multiplier

CheckOutStudentAction:
  proRatedHours = min(
      actualDurationInMinutes() / 60,
      event.duration_hours
  )
  IF proRatedHours < 0.5 (30min) → 0 points
  pointsEarned = round(proRatedHours × event.effectivePoints())

CreditPointsAction:
  - Creates PointsTransaction
  - Database trigger auto-updates User.points_balance

User.points_balance is READ-ONLY (protected in boot())
```

### Grade Upgrade

```
UpgradeGradeAction thresholds:
  novice      → pilier:     50 hours
  pilier      → ambassadeur: 150 hours
  ambassadeur → max:        no upgrade

Automatic after check-out via CheckOutStudentAction
```

### Event Approval

```
- New partner events: status = 'pending'
- Certified partner events: auto-approve (status = 'approved')
- Admin approval required for pending events
- Approved partners gain 'event.approve' permission
```

### QR Token Generation

```
EventUser::booted():
  - On creating, if qr_token empty: set qr_token = Str::uuid()
  - One token per registration (not per event)
  - Token used for check-in scan: route('checkin.scan', token)
```

---

## 15. Database Constraints

- **RESTRICT on DELETE** for `event_user` pivot (preserves history)
- **RESTRICT on DELETE** for `conversation_user` pivot
- Users and Events cannot be hard-deleted (`forceDelete()` throws)
- PointsTransactions are immutable (no update/delete)
- Unique constraints on pivots prevent duplicates
- Points balance is computed from ledger via triggers

---

*Generated by codebase analysis. This is the canonical class diagram for ActTogether v2.0.*
