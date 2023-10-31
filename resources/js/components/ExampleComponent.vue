<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  created() {
    // Subscribe to a Pusher channel
    const channel = this.$pusher.subscribe('verification'); // Replace with your channel name

    // Listen for a specific event on the channel
    channel.bind('VerificationNotification', (data) => { // Replace with your event name
      console.log('Received data:', data);
      // Handle the data from the event
    });
  },
};
</script>
