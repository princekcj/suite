<x-cards.notification :notification="$notification" link="javascript:;" :image="user()->image_url" :title="$notification->data['title']" :text="$notification->data['heading']" :time="$notification->created_at" />