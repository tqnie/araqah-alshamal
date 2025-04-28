<?php 
use App\Models\Post; 
use Livewire\Volt\Component;

new class extends Component
{
    public $posts = [];
    public function mount()
    {
        Post::active()->limit(5)->each(function ($post) { 
            $this->posts[] = [
                'title' => $post->title,
                'excerpt' => $post->excerpt, 
                'body' => $post->body,
                'category' => $post->category->name,
                'category_id' => $post->category_id,
                'slug' => $post->slug,
                'meta_description' => $post->meta_description,
                'meta_keywords' => $post->meta_keywords,
                'status' => $post->status,
                'featured' => $post->featured,
                'user_id' => $post->user_id,
                'ago' => $post->created_at->diffForHumans(),
                'image' => $post->image, 
            ];
        });
    }
}; ?>
         <section class="latest-blogs">
            <div class="container">
                <div class="sec-title">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h3>{{setting('section1_title')}}</h3>
                            <p>{{setting('section1_subtitle')}}</p>
                        </div>
                        {{-- setting('section1_url')?? --}}
                        <div class="col-md-auto "><a  wire:navigate href="{{route('blogs.index')}}" class="btn btn-outline-dark">مشاهدة المزيد</a></div>
                    </div>
                </div>
                <div class="blogs">
                    <div class="row">
                       
                        @foreach ($posts as $post)
                        <div class="col-12 col-md-4">
                            <a href="{{route('blog.show',$post['slug'])}}" wire:navigate class="blog-card">
                                <div class="head">
                                    <span class="badge bg-info"></span>
                                    <span class="date">{{$post['ago']}}</span>
                                </div>
                                <h6>{{$post['title']}}</h6>
                                <div class="blog-card-bottom">
                                    <div class="text">{{$post['excerpt']}}</div>
                                    <div class="ico">
                                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12.4114H5" stroke="#DFBD66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 19.4114L5 12.4114L12 5.41138" stroke="#DFBD66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
        
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>