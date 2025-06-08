document.addEventListener('livewire:initialized', () => {
    const chatMessages = document.getElementById('chat-messages');
    if(chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;

        Livewire.on('message-sent', () => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    }
});
