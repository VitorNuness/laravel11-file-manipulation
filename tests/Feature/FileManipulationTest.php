<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileManipulationTest extends TestCase
{
    /** @test */
    public function can_be_upload_a_file(): void
    {
        $storage = Storage::fake('files');
        $file = UploadedFile::fake()->create(
            fake()->filePath(),
            0,
            'txt'
        );

        $response = $this->post('/', [
            'file' => $file,
        ]);

        $storage->assertExists($file->hashName());
    }

    /** @test */
    public function can_be_updated_a_file(): void
    {
        $storage = Storage::fake('files');
        $file = UploadedFile::fake()->create(
            fake()->filePath(),
            0,
            'txt'
        );
        $content = fake()->text();
        $storage->put(
            $file->hashName(),
            $file->getContent()
        );

        $response = $this->put('/', [
            'file'    => $file,
            'content' => $content,
        ]);

        $this->assertEquals(
            $content,
            $storage->get($file->hashName())
        );
    }


    /** @test */
    public function can_be_delete_a_file(): void
    {
        $file = UploadedFile::fake()->create(
            fake()->filePath(),
            0,
            'txt'
        );
        $storage = Storage::fake('files');
        $storage->put(
            $file->hashName(),
            $file->getContent()
        );

        $response = $this->delete('/', [
            'file' => $file,
        ]);

        $storage->assertMissing($file->hashName());
    }

    /** @test */
    public function can_be_show_a_file(): void
    {
        $storage = Storage::fake('files');
        $file = UploadedFile::fake()->create(
            fake()->filePath(),
            0,
            'txt'
        );
        $storage->put(
            $file->hashName(),
            fake()->text()
        );

        $response = $this->get('/' . $file->hashName());

        $this->assertEquals(
            $storage->get($file->hashName()),
            $response->getContent()
        );
    }

    /** @test */
    public function can_be_get_a_file_url(): void
    {
        $storage = Storage::fake('files');
        $file = UploadedFile::fake()->create(
            fake()->filePath(),
            0,
            'txt'
        );
        $storage->put(
            $file->hashName(),
            fake()->text()
        );

        $response = $this->get('/' . $file->hashName() . '/url');

        $response->assertSuccessful();
        $this->assertEquals(
            $storage->url($file->hashName()),
            $response->getContent()
        );
    }
}
