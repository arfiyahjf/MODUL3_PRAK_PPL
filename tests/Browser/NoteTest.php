<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NoteTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
public function test_user_can_create_note()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();

            $browser->loginAs($user)
                    ->visit('/notes/create')
                    ->type('title', 'Catatan Baru')
                    ->type('content', 'Isi dari catatan baru.')
                    ->press('Save')
                    ->assertSee('Catatan Baru');
        });
    }
    public function test_user_can_edit_note()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();
            $note = $user->notes()->first(); 
    
            $browser->loginAs($user)
                    ->visit("/notes/{$note->id}/edit")
                    ->type('title', 'Judul Baru')
                    ->type('content', 'Konten yang sudah diubah')
                    ->press('Update') 
                    ->assertSee('Judul Baru');
        });
    }
    public function test_user_can_view_notes()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();
    
            $browser->loginAs($user)
                    ->visit('/notes') 
                    ->assertSee('Daftar Catatan'); 
        });
    }
    public function test_user_can_delete_note()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();
            $note = $user->notes()->create([
                'title' => 'Untuk Dihapus',
                'content' => 'Ini akan dihapus',
            ]);
    
            $browser->loginAs($user)
                    ->visit('/notes')
                    ->press("@delete-note-{$note->id}") 
                    ->assertDontSee('Untuk Dihapus');
        });
    }
    public function test_user_can_logout()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();
    
            $browser->loginAs($user)
                    ->visit('/home')
                    ->click('@logout-button')
                    ->assertPathIs('/login');
        });
    }        
}
